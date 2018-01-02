<?php
/* ----------------------------------------------------------------------------------- */
# Create post_type
/* ----------------------------------------------------------------------------------- */
add_action('init', 'create_product_post_type');

function create_product_post_type(){
    register_post_type('product', array(
        'labels' => array(
            'name' => __('Products'),
            'singular_name' => __('Products'),
            'add_new' => __('Add new'),
            'add_new_item' => __('Add new Product'),
            'new_item' => __('New Product'),
            'edit' => __('Edit'),
            'edit_item' => __('Edit Product'),
            'view' => __('View Product'),
            'view_item' => __('View Product'),
            'search_items' => __('Search Products'),
            'not_found' => __('No Product found'),
            'not_found_in_trash' => __('No Product found in trash'),
        ),
        'public' => true,
        'show_ui' => true,
        'publicy_queryable' => true,
        'exclude_from_search' => false,
        'menu_position' => 20,
        'hierarchical' => false,
        'query_var' => true,
        'supports' => array(
            'title', 'editor', 'author', 
            //'excerpt', 'custom-fields', 'comments', 'thumbnail', 
        ),
        'rewrite' => array('slug' => 'product', 'with_front' => false),
        'can_export' => true,
        'description' => __('Product description here.'),
        //'taxonomies' => array('post_tag'),
    ));
}
/* ----------------------------------------------------------------------------------- */
# Create taxonomy
/* ----------------------------------------------------------------------------------- */
add_action('init', 'create_product_taxonomies');

function create_product_taxonomies(){
    register_taxonomy('product_category', 'product', array(
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'query_var' => true,
        'labels' => array(
            'name' => __('Product Categories'),
            'singular_name' => __('Product Categories'),
            'add_new' => __('Add New'),
            'add_new_item' => __('Add New Category'),
            'new_item' => __('New Category'),
            'search_items' => __('Search Categories'),
        ),
        'rewrite' => array('slug' => 'product-category', 'with_front' => false),
    ));
    register_taxonomy('product_color', 'product', array(
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'query_var' => true,
        'labels' => array(
            'name' => __('Product Colors'),
            'singular_name' => __('Product Colors'),
            'add_new' => __('Add New'),
            'add_new_item' => __('Add New Color'),
            'new_item' => __('New Color'),
            'search_items' => __('Search Colors'),
        ),
    ));
    register_taxonomy('product_size', 'product', array(
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'query_var' => true,
        'labels' => array(
            'name' => __('Product Sizes'),
            'singular_name' => __('Product Sizes'),
            'add_new' => __('Add New'),
            'add_new_item' => __('Add New Size'),
            'new_item' => __('New Color'),
            'search_items' => __('Search Sizes'),
        ),
    ));
}

//if(function_exists("qtrans_modifyTermFormFor")){
//    add_action('product_category_add_form', 'qtrans_modifyTermFormFor');
//    add_action('product_category_edit_form', 'qtrans_modifyTermFormFor');
//    add_action('product_factor_add_form', 'qtrans_modifyTermFormFor');
//    add_action('product_factor_edit_form', 'qtrans_modifyTermFormFor');
//    add_action('product_color_add_form', 'qtrans_modifyTermFormFor');
//    add_action('product_color_edit_form', 'qtrans_modifyTermFormFor');
//    add_action('product_material_add_form', 'qtrans_modifyTermFormFor');
//    add_action('product_material_edit_form', 'qtrans_modifyTermFormFor');
//}

// Show filter
add_action('restrict_manage_posts','restrict_product_by_product_category');
function restrict_product_by_product_category() {
    global $wp_query, $typenow;
    if ($typenow=='product') {
        $taxonomies = array('product_category', 'product_color');
        foreach ($taxonomies as $taxonomy) {
            $category = get_taxonomy($taxonomy);
            wp_dropdown_categories(array(
                'show_option_all' =>  __("$category->label"),
                'taxonomy'        =>  $taxonomy,
                'name'            =>  $taxonomy,
                'orderby'         =>  'name',
                'selected'        =>  $wp_query->query['term'],
                'hierarchical'    =>  true,
                'depth'           =>  3,
                'show_count'      =>  true, // Show # listings in parens
                'hide_empty'      =>  true, // Don't show businesses w/o listings
            ));
        }
    }
}

// Get post where filter condition

add_filter( 'posts_where' , 'products_where' );
function products_where($where) {
    if (is_admin()) {
        global $wpdb;
        
        $wp_posts = $wpdb->posts;
        $term_relationships = $wpdb->term_relationships;
        $term_taxonomy = $wpdb->term_taxonomy;

        $product_category = intval(getRequest('product_category'));
        $product_color = intval(getRequest('product_color'));
        if ($product_category > 0 or $product_color > 0) {
            $where .= " AND $wp_posts.ID IN (SELECT DISTINCT {$term_relationships}.object_id FROM {$term_relationships} 
                WHERE {$term_relationships}.term_taxonomy_id IN (
                    SELECT DISTINCT {$term_taxonomy}.term_taxonomy_id FROM {$term_taxonomy} ";
            
            if ($product_category > 0 and $product_color > 0) {
                $where .= " WHERE {$term_taxonomy}.term_id IN ($product_category, $product_color) 
                                AND {$term_taxonomy}.taxonomy IN ('product_category', 'product_color' ) ) )";
            } elseif ($product_category > 0) {
                $where .= " WHERE {$term_taxonomy}.term_id = $product_category 
                                AND {$term_taxonomy}.taxonomy = 'product_category') )";
            } elseif ($product_color > 0) {
                $where .= " WHERE {$term_taxonomy}.term_id = $product_color 
                                AND {$term_taxonomy}.taxonomy = 'product_color') )";
            }
                            
//            $where = str_replace("AND 0 = 1", "", $where);
            $where = str_replace("0 = 1", "1 = 1", $where);
        }
    }
    return $where;
}
/* ----------------------------------------------------------------------------------- */
# Meta box
/* ----------------------------------------------------------------------------------- */
$product_meta_box = array(
    'id' => 'product-meta-box',
    'title' => 'Product Information',
    'page' => 'product',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Price',
            'desc' => 'Example: 100000',
            'id' => 'price',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => 'Sale (%)',
            'desc' => 'Example: 10',
            'id' => 'sale',
            'type' => 'text',
            'std' => '',
        ),
        array(
            'name' => 'Status',
            'desc' => '',
            'id' => 'tinh_trang',
            'type' => 'radio',
            'std' => 'in_stock',
            'options' => array(
                'in_stock' => 'In stock',
                'out_of_stock' => 'Out of stock',
                'coming_soon' => 'Coming soon',
            )
        ),
));

// Add product meta box
if(is_admin()){
    add_action('admin_menu', 'product_add_box');
    add_action('save_post', 'product_add_box');
    add_action('save_post', 'product_save_data');
    add_action('publish_product', 'product_publish_data');
}

function product_add_box(){
    global $product_meta_box;
    add_meta_box($product_meta_box['id'], $product_meta_box['title'], 'product_show_box', $product_meta_box['page'], $product_meta_box['context'], $product_meta_box['priority']);
}
/**
 * Callback function to show fields in product meta box
 * @global array $product_meta_box
 * @global Object $post
 * @global array $area_fields
 */
function product_show_box() {
    global $product_meta_box, $post;
    custom_output_meta_box($product_meta_box, $post);    
}
/**
 * Save data from product meta box
 * @global array $product_meta_box
 * @global array $area_fields
 * @param Object $post_id
 * @return 
 */
function product_save_data($post_id) {
    global $product_meta_box, $area_fields;
    custom_save_meta_box($product_meta_box, $post_id);
    
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    foreach ($area_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if (isset($_POST[$field['id']]) && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
    
    return $post_id;
}

function product_publish_data($post_id){
    $purchases = get_post_meta($post_id, "purchases", true);
    
    if(!$purchases or $purchases == ""){
        if( ( $_POST['post_status'] == 'publish' ) && ( $_POST['original_post_status'] != 'publish' ) ) {
            update_post_meta($post_id, 'purchases', 0);
        }
    }
    
    return $post_id;
}
/***************************************************************************/
// ADD NEW COLUMN  
function product_columns_head($defaults) {
    //$defaults['is_most'] = 'Nổi bật';
    $defaults['tinh_trang'] = 'Status';
    $defaults['orders'] = 'Orders';
    return $defaults;
}

// SHOW THE COLUMN
function product_columns_content($column_name, $post_id) {
    switch ($column_name) {
        case 'is_most':
            $is_most = get_post_meta( $post_id, 'is_most', true );
            if($is_most == 1){
                echo '<a href="edit.php?update_is_most=true&post_id=' . $post_id . '&is_most=' . $is_most . '&redirect_to=' . urlencode(getCurrentRquestUrl()) . '">Yes</a>';
            }else{
                echo '<a href="edit.php?update_is_most=true&post_id=' . $post_id . '&is_most=' . $is_most . '&redirect_to=' . urlencode(getCurrentRquestUrl()) . '">No</a>';
            }
            break;
        case 'tinh_trang':
            $tinh_trang = get_post_meta($post_id, "tinh_trang", true);
            switch ($tinh_trang) {
                case "in_stock":
                    echo '<span class="bold" style="color:green">In stock</span>';
                    break;
                case "out_of_stock":
                    echo '<span class="bold" style="color:orange">Out of stock</span>';
                    break;
                case "coming_soon":
                    echo '<span class="bold" style="color:red">Coming soon</span>';
                    break;
                default:
                    break;
            }
            break;
        case 'orders':
            echo '<a href="admin.php?page=nvt_orders&product_id=' . $post_id . '" target="_blank">View</a>';
            break;
        default:
            break;
    }
}

// Update is most stataus
function update_product_is_most(){
    if(getRequest('update_is_most') == 'true'){
        $post_id = getRequest('post_id');
        $is_most = getRequest('is_most');
        $redirect_to = urldecode(getRequest('redirect_to'));
        if($is_most == 1){
            update_post_meta($post_id, 'is_most', 0);
        }else{
            update_post_meta($post_id, 'is_most', 1);
        }
        header("location: $redirect_to");
        exit();
    }
}

add_filter('manage_product_posts_columns', 'product_columns_head');  
add_action('manage_product_posts_custom_column', 'product_columns_content', 10, 2);  
add_filter('admin_init', 'update_product_is_most');  

function sortable_product_is_most_column( $columns ) {  
    $columns['is_most'] = 'is_most';
    $columns['tinh_trang'] = 'tinh_trang';
    return $columns;
}

function product_is_most_orderby( $query ) {  
    if( ! is_admin() )  
        return;  
  
    $orderby = $query->get( 'orderby');  
  
    switch ($orderby) {
        case 'is_most':
            $query->set('meta_key','is_most');  
            $query->set('orderby','meta_value_num');  
            break;
        case 'tinh_trang':
            $query->set('meta_key','tinh_trang');  
            $query->set('orderby','meta_value');  
            break;
        default:
            break;
    }
}

add_filter( 'manage_edit-product_sortable_columns', 'sortable_product_is_most_column' );  
add_action( 'pre_get_posts', 'product_is_most_orderby' );  

# Add custom field to quick edit

//add_action( 'bulk_edit_custom_box', 'quickedit_products_custom_box', 10, 2 );
add_action('quick_edit_custom_box', 'quickedit_products_custom_box', 10, 2);

function quickedit_products_custom_box( $col, $type ) {
    if( $col != 'tinh_trang' || $type != 'product' ) {
        return;
    }

    $tinh_trang = array(
        'in_stock' => 'In stock',
        'out_of_stock' => 'Out of stock',
        'coming_soon' => 'Coming soon',
    );
?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col product-custom-fields">
            <div class="inline-edit-group">
                <label class="alignleft">
                    <span class="title">Price</span>
                    <input type="text" name="price" id="price" value="" />
                    <span class="spinner" style="display: none;"></span>
                </label>
            </div>
            <div class="inline-edit-group">
                <label class="alignleft">
                    <span class="title">Sale (%)</span>
                    <input type="text" name="sale" id="sale" value="" />
                    <span class="spinner" style="display: none;"></span>
                </label>
            </div>
            <div class="inline-edit-group">
                <label class="alignleft">
                    <span class="title">Status</span>
                    <select name="tinh_trang" id='tinh_trang'>
                        <?php
                        foreach ($tinh_trang as $k => $v) {
                            echo "<option value='{$k}'>{$v}</option>";
                        }
                        ?>
                    </select>
                    <span class="spinner" style="display: none;"></span>
                </label>
            </div>
        </div>
    </fieldset>
<?php
}

add_action('save_post', 'product_save_quick_edit_data');
 
function product_save_quick_edit_data($post_id) {
    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
    // to do anything
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        return $post_id;   
    // Check permissions
    if ( 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return $post_id;
    } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;
    }
    // OK, we're authenticated: we need to find and save the data
    $post = get_post($post_id);
    $fields = array('price', 'sale', 'tinh_trang');
    foreach ($fields as $field) {
        if (isset($_POST[$field]) && ($post->post_type != 'revision')) {
            $meta = esc_attr($_POST[$field]);
            if ($meta)
                update_post_meta( $post_id, $field, $meta);
        }
    }
    
    return $post_id;
}

add_action( 'admin_print_scripts-edit.php', 'product_enqueue_edit_scripts' );
function product_enqueue_edit_scripts() {
   wp_enqueue_script( 'product-admin-edit', get_bloginfo( 'stylesheet_directory' ) . '/libs/js/quick_edit.js', array( 'jquery', 'inline-edit-post' ), '', true );
}

//////////////////
//add extra fields to tag edit form hook
add_action('product_category_add_form_fields', 'product_add_extra_tag_fields');
//add_action('edit_tag_form_fields', 'product_extra_tag_fields');
add_action('product_category_edit_form_fields', 'product_extra_tag_fields');

//add extra fields to category edit form callback function
function product_add_extra_tag_fields() {
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_discount"><?php _e('Discount (%)'); ?></label></th>
        <td>
            <input type="text" name="tag_meta[discount]" id="tag_meta_discount" value="" /><br />
            <span class="description">Discount for all product in this category. Example: 20</span><br /><br />
        </td>
    </tr>
    <?php
}
function product_extra_tag_fields($tag) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $tag_meta = get_option("tag_$t_id");
    ?>

    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_discount"><?php _e('Discount (%)'); ?></label></th>
        <td>
            <input type="text" name="tag_meta[discount]" id="tag_meta_discount" value="<?php echo $tag_meta['discount'] ? $tag_meta['discount'] : ''; ?>" /><br />
            <span class="description">Discount for all product in this category. Example: 20</span>
        </td>
    </tr>
    <?php
}

//add extra fields to tag edit form hook
add_action('product_color_add_form_fields', 'product_color_add_extra_tag_fields');
//add_action('edit_tag_form_fields', 'product_extra_tag_fields');
add_action('product_color_edit_form_fields', 'product_color_extra_tag_fields');

//add extra fields to category edit form callback function
function product_color_add_extra_tag_fields() {
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_img"><?php _e('Color Image'); ?></label></th>
        <td>
            <input type="text" name="tag_meta[img]" id="tag_meta_img" style="width:80%;" value=""/>
            <button type="button" onclick="uploadByField('tag_meta_img')" class="button button-upload" id="upload_tag_meta_img_button" />Upload</button><br />
            <span class="description">Size: 32x32. Use full URL with http://</span><br /><br />
        </td>
    </tr>
    <?php
}
function product_color_extra_tag_fields($tag) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $tag_meta = get_option("tag_$t_id");
    ?>

    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_img"><?php _e('Color Image'); ?></label></th>
        <td>
            <input type="text" name="tag_meta[img]" id="tag_meta_img" style="width:84%;" value="<?php echo $tag_meta['img'] ? $tag_meta['img'] : ''; ?>">
            <button type="button" onclick="uploadByField('tag_meta_img')" class="button button-upload" id="upload_tag_meta_img_button" />Upload</button><br />
            <span class="description">Size: 32x32. Use full URL with http://</span>
        </td>
    </tr>
    <?php
}

// save extra tag extra fields hook
add_action('edited_terms', 'product_save_extra_tag_fileds');
add_action('create_term', 'product_save_extra_tag_fileds');

// save extra tag extra fields callback function
function product_save_extra_tag_fileds($term_id) {
    if (isset($_POST['tag_meta'])) {
        $t_id = $term_id;
        $tag_meta = get_option("tag_$t_id");
        $tag_keys = array_keys($_POST['tag_meta']);
        foreach ($tag_keys as $key) {
            if (isset($_POST['tag_meta'][$key])) {
                $tag_meta[$key] = stripslashes_deep($_POST['tag_meta'][$key]);
            }
        }
        //save the option array
        update_option("tag_$t_id", $tag_meta);
    }
}

//these filters will only affect custom column, the default column will not be affected
//filter: manage_edit-{$taxonomy}_columns
function product_color_custom_column_header($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['thumb'] = __('Image');

    unset( $columns['cb'] );

    return array_merge( $new_columns, $columns );
}

add_filter("manage_edit-product_color_columns", 'product_color_custom_column_header', 10);

function product_color_column_content($columns, $column_name, $tax_id) {
    $tag_meta = get_option("tag_$tax_id");
    //for multiple custom column, you may consider using the column name to distinguish
    if ($column_name === 'thumb') {
        $columns = '<span><img src="' . $tag_meta['img'] . '" alt="' . __('Thumbnail') . '" class="wp-post-image" /></span>';
    }
    return $columns;
}

add_action("manage_product_color_custom_column", 'product_color_column_content', 10, 3);