<?php

function random_shop_update() {
    global $wpdb;
    $table_name = $wpdb->prefix . "name_shops";
    $id = $_GET["id"];
    $name = $wpdb->get_results($wpdb->prepare("SELECT id,name from $table_name WHERE id = %s LIMIT 1", $id));
    $name = sizeof($name) > 0 ? $name[0]->name : NULL;
    //update
    if (isset($_POST['update'])) {
        $name = sanitize_text_field($_POST["name"]);
        $rows = $wpdb->get_results("SELECT id,name from $table_name WHERE name = '$name' LIMIT 1");
        if(sizeof($rows) > 0){
          echo "<script>alert('You can\'t use duplicated name for this shop');</script>";
          if (isset($_GET['noheader']))
            require_once(ABSPATH . 'wp-admin/admin-header.php');
        }else{
          $wpdb->update(
                  $table_name, //table
                  array('name' => $name), //data
                  array('ID' => $id), //where
                  array('%s'), //data format
                  array('%s') //where format
          );
          echo "<script>alert('Shop updated');</script>";
          sleep(1);
          wp_redirect( admin_url( 'admin.php?page=random_shop_list' ));exit;
        }
    }
//delete
    else if (isset($_POST['delete'])) {
        $name = sanitize_text_field($_POST["name"]);
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %s", $id));
        echo "<script>alert('Shop deleted');</script>";
        wp_redirect( admin_url( 'admin.php?page=random_shop_list' ));exit;
    } else {//selecting value to update
        $shops = $wpdb->get_results($wpdb->prepare("SELECT id,name from $table_name where id=%s", $id));
        foreach ($shops as $s) {
            $name = $s->name;
        }
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/random_shop/assets/css/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Shops</h2>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']."&noheader=true"; ?>">
                <table class='wp-list-table widefat fixed'>
                    <tr><th>Name</th><td><input type="text" name="name" value="<?php echo $name; ?>"/></td></tr>
                </table>
                <input type='submit' name="update" value='Save' class='button'> &nbsp;&nbsp;
                <input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Delete this shop?')">
            </form>
    </div>
    <?php
}
