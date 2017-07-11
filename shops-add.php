<?php

function random_shop_add() {
    //insert
    if (isset($_POST['insert'])) {
        $message = "Shop inserted";
        $name = sanitize_text_field($_POST["name"]);
        global $wpdb;
        $table_name = $wpdb->prefix . "name_shops";

        $rows = $wpdb->get_results("SELECT id,name from $table_name WHERE name = '$name' LIMIT 1");
        if(sizeof($rows) > 0){
          if (isset($_GET['noheader']))
            require_once(ABSPATH . 'wp-admin/admin-header.php');
          $message = "You can't add duplicated shops";
        }else{
          $wpdb->insert(
                  $table_name, //table
                  array('name' => $name), //data
                  array('%s') //data format
          );
          wp_redirect( admin_url( 'admin.php?page=random_shop_list' ));exit;
        }
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/random_shop/assets/css/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Add New Shop</h2>
        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']."&noheader=true"; ?>">
            <table class='wp-list-table widefat fixed'>
                <tr>
                    <th class="ss-th-width">Shop</th>
                    <td><input type="text" name="name" value="" class="ss-field-width" autocomplete="off" /></td>
                </tr>
            </table>
            <input type='submit' name="insert" value='Save' class='button'>
        </form>
    </div>
    <?php
}
