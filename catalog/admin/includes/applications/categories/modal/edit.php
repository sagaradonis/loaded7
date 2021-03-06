<?php
/*
  $Id: edit.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<style>
#editCategory { padding-bottom:20px; }
</style>
<script>
function editCategory(id) {
  var accessLevel = '<?php echo $_SESSION['admin']['access'][$lC_Template->getModule()]; ?>';
  if (parseInt(accessLevel) < 3) {
    $.modal.alert('<?php echo $lC_Language->get('ms_error_no_access');?>');
    return false;
  }
  var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&cid=CID&action=getFormData'); ?>'
  $.getJSON(jsonLink.replace('CID', parseInt(id)),
    function (data) {
      if (data.rpcStatus == -10) { // no session
        var url = "<?php echo lc_href_link_admin(FILENAME_DEFAULT, 'login'); ?>";
        $(location).attr('href',url);
      }
      if (data.rpcStatus != 1) {
        $.modal.alert('<?php echo $lC_Language->get('ms_error_retrieving_data'); ?>');
        return false;
      }
      $.modal({
          content: '<div id="editCategory">'+
                   '  <div id="editCategoryForm">'+
                   '    <form name="cEdit" id="cEdit" action="" method="post" enctype="multipart/form-data">'+
                   '      <p><?php echo $lC_Language->get('introduction_edit_category'); ?></p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="parent_id" class="label" style="width:33%;"><?php echo $lC_Language->get('field_parent_category'); ?></label>'+
                   '        <?php echo lc_draw_pull_down_menu('parent_id', null, null, 'class="select" style="width:73%;" id="editParentId"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="categories_name" class="label" style="width:33%;"><?php echo $lC_Language->get('field_name'); ?></label>'+
                   '        <span id="editCategoryNames"></span>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <span id="categoryImage"></span>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="categories_image" class="label" style="width:33%;"><?php echo $lC_Language->get('field_image'); ?></label>'+
                   '        <?php echo lc_draw_file_field('categories_image', true, 'class="file"'); ?>'+
                   '      </p>'+
                   '      <p class="button-height inline-label">'+
                   '        <label for="sort_order" class="label" style="width:33%;"><?php echo $lC_Language->get('field_sort_order'); ?></label>'+
                   '        <?php echo lc_draw_input_field('sort_order', null, 'class="input" id="editSortOrder"'); ?>'+
                   '      </p>'+
                   '    </form>'+
                   '  </div>'+
                   '</div>',
          title: '<?php echo $lC_Language->get('modal_heading_edit_category'); ?>',
          width: 500,
          scrolling: false,
          actions: {
            'Close' : {
              color: 'red',
              click: function(win) { win.closeModal(); }
            }
          },
          buttons: {
            '<?php echo $lC_Language->get('button_cancel'); ?>': {
              classes:  'glossy',
              click:    function(win) { win.closeModal(); }
            },
            '<?php echo $lC_Language->get('button_save'); ?>': {
              classes:  'blue-gradient glossy',
              click:    function(win) {
                var bValid = $("#cEdit").validate({
                  rules: {
                    parent_id: { required: true },
                    'categories_name[1]': { required: true },
                    sort_order: { number: true }
                  },
                  invalidHandler: function() {
                  }
                }).form();
                if (bValid) {
                  var url = '<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '=' . $_GET[$lC_Template->getModule()] . '&cid=CID&action=save'); ?>';
                  var actionUrl = url.replace('CID', parseInt(id));
                  $("#cEdit").attr("action", actionUrl);
                  $("#cEdit").submit();
                  win.closeModal();
                }
              }
            }
          },
          buttonsLowPadding: true
      });
      $("#editParentId").empty();  // clear the old values
      $.each(data.categoriesArray, function(val, text) {
        var selected = (data.parentCategory == val) ? 'selected="selected"' : '';
        if(data.parentCategory == val) {
          $("#editParentId").closest("span + *").prevAll("span.select-value:first").text(text);
        }
        $("#editParentId").append(
           $("<option " + selected + "></option>").val(val).html(text)
        );
      });
      $("#editCategoryNames").html(data.categoryNames);
      $("#categoryImage").html(data.categoryImage);
      $("#editSortOrder").val(data.cData.sort_order);
      $("[name=parent_id]").focus();
    }
  );
}

function focusFirstFormField() {
  try {
    var selector = $("#cEdit");
    if (selector.length >= 1 && selector[0] && selector[0].elements && selector[0].elements.length > 0) {
      var elements = selector[0].elements;
      var length = elements.length;
      for (var i = 0; i < length; i++) {
        var elem = elements[i];
        var type = elem.type;

        // ignore images, hidden fields, buttons, and submit-buttons
        if (elem.style.display != "none" /* check for visible */ && type != "image" && type != "hidden" && type != "button" && type != "submit") {
          elem.focus();
          break;
        }
      }
    }
  }
  catch(err) { /* ignore error if any */ }
}
</script>