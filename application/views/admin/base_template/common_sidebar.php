 
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
 <a href="<?php echo site_url(); ?>" class="brand-link">
   <img src="<?php echo base_url(ADMIN_THEME_BASE."/img/logo.jpg") ?>" alt="Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
          <span class="brand-text font-weight-light"><?php echo APP_NAME; ?></span>
 </a>
    
     <div class="sidebar">
    
       <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        
          <?php
            
            $this->db->where("draft","0");
            $count_products = $this->db->count_all_results("products");

            $this->db->where("draft","0");
            $count_categories = $this->db->count_all_results("categories");
            
            $this->db->where("draft","0");
            $this->db->where("user_type_id",USER_CUSTOMER);
            $count_user_customer = $this->db->count_all_results("users");
            
            $this->db->where("draft","0");
            $count_branches = $this->db->count_all_results("branches");

            $this->db->where("draft","0");
            $count_orders = $this->db->count_all_results("orders");            
            
            $this->db->where("draft","0");
            $this->db->where("status",ORDER_PENDING);
            $count_orders_pending = $this->db->count_all_results("orders");

            $this->db->where("draft","0");
            $this->db->where("status",ORDER_CONFIRMED);
            $count_orders_confirmed = $this->db->count_all_results("orders");

            $this->db->where("draft","0");
            $this->db->where("status",ORDER_OUT_OF_DELIVEY);
            $count_orders_outofdelivery = $this->db->count_all_results("orders");

            $this->db->where("draft","0");
            $this->db->where("status",ORDER_DELIVERED);
            $count_orders_delivered = $this->db->count_all_results("orders");
            
            $menu_array = array();
            
			if(_is_admin())
			{
				$dashboardLink=site_url("admin/dashboard");
			}			
			else
            {
                return redirect("login");
            }
            
             $menu_array[] = array("menu_title"=>_l("Dashboard"),"link"=>$dashboardLink,"menu_icon"=>"nav-icon fas fa-tachometer-alt","badge"=>""); 
            
             $product_menu[] = array("menu_title"=>_l("Products"),"link"=>site_url("admin/products"),"menu_icon"=>"nav-icon fas fa-shopping-cart","badge"=>$count_products);
             $product_menu[] = array("menu_title"=>_l("Categories"),"link"=>site_url("admin/categories"),"menu_icon"=>"nav-icon fas fa-cog","badge"=>$count_categories);
             $menu_array[] = array("menu_title"=>_l("Products"),"link"=>"javascript:;","menu_icon"=>"nav-icon fas fa-industry","badge"=>"","sub_menu"=>$product_menu);                
                
             $menu_array[] = array("menu_title"=>_l("Discounts"),"link"=>site_url("admin/discounts"),"menu_icon"=>"nav-icon fas fa-tag","badge"=>"");
             $menu_array[] = array("menu_title"=>_l("Coupons"),"link"=>site_url("admin/coupons"),"menu_icon"=>"nav-icon fas fa-tags","badge"=>"");
             $menu_array[] = array("menu_title"=>_l("Banners"),"link"=>site_url("admin/banners"),"menu_icon"=>"nav-icon fas fa-image","badge"=>"");              
             $menu_array[] = array("menu_title"=>_l("Support Inbox"),"link"=>site_url("admin/contacts"),"menu_icon"=>"nav-icon fas fa-envelope","badge"=>"");              
             $menu_array[] = array("menu_title"=>_l("Notifications"),"link"=>site_url("admin/notifications"),"menu_icon"=>"nav-icon fas fa-bell","badge"=>"");              
                          
             $menu_array[] = array("menu_title"=>_l("App Users"),"link"=>site_url("admin/users/appusers"),"menu_icon"=>"nav-icon fas fa-users","badge"=>$count_user_customer);            
             $menu_array[] = array("menu_title"=>_l("Branches"),"link"=>site_url("admin/branches"),"menu_icon"=>"nav-icon fas fa-truck","badge"=>$count_branches);                             
             $menu_array[] = array("menu_title"=>_l("Delivery Boy"),"link"=>site_url("admin/deliveryboy"),"menu_icon"=>"nav-icon fas fa-truck","badge"=>"");                             
           
             $order_menu[] = array("menu_title"=>_l("Orders"),"link"=>site_url("admin/orders"),"menu_icon"=>"nav-icon fas fa-cog","badge"=>$count_orders);
             $order_menu[] = array("menu_title"=>_l("Order Pendings"),"link"=>site_url("admin/orders/pending"),"menu_icon"=>"nav-icon fas fa-cog","badge"=>$count_orders_pending);
             $order_menu[] = array("menu_title"=>_l("Order Confirmed"),"link"=>site_url("admin/orders/confirmed"),"menu_icon"=>"nav-icon fas fa-cog","badge"=>$count_orders_confirmed);
             $order_menu[] = array("menu_title"=>_l("Order On Delivery"),"link"=>site_url("admin/orders/outdelivery"),"menu_icon"=>"nav-icon fas fa-cog","badge"=>$count_orders_outofdelivery);
             $order_menu[] = array("menu_title"=>_l("Order Delivered"),"link"=>site_url("admin/orders/delivered"),"menu_icon"=>"nav-icon fas fa-cog","badge"=>$count_orders_delivered);
             $menu_array[] = array("menu_title"=>_l("Orders"),"link"=>"javascript:;","menu_icon"=>"nav-icon fas fa-cart-arrow-down","badge"=>"","sub_menu"=>$order_menu);

             $settings_menu[] = array("menu_title"=>_l("General Settings"),"link"=>site_url("setting"),"menu_icon"=>"nav-icon fas fa-cog","badge"=>"");
             $settings_menu[] = array("menu_title"=>_l("App Settings"),"link"=>site_url("setting/app"),"menu_icon"=>"nav-icon fas fa-cog","badge"=>"");
             $settings_menu[] = array("menu_title"=>_l("Billing Settings"),"link"=>site_url("setting/billing"),"menu_icon"=>"nav-icon fas fa-cog","badge"=>"");
             $settings_menu[] = array("menu_title"=>_l("SMS Settings"),"link"=>site_url("setting/sms_setting"),"menu_icon"=>"nav-icon fas fa-cog","badge"=>"");
             $settings_menu[] = array("menu_title"=>_l("Payment Settings"),"link"=>site_url("setting/payment"),"menu_icon"=>"nav-icon fas fa-cog","badge"=>"");
             $settings_menu[] = array("menu_title"=>_l("Email Settings"),"link"=>site_url("setting/email"),"menu_icon"=>"nav-icon fas fa-cog","badge"=>"");
             $settings_menu[] = array("menu_title"=>_l("Email Templates"),"link"=>site_url("admin/emailtemplates"),"menu_icon"=>"nav-icon fas fa-cog","badge"=>"");
             $settings_menu[] = array("menu_title"=>_l("App Pages"),"link"=>site_url("admin/apppages"),"menu_icon"=>"nav-icon fas fa-cog","badge"=>"");
             $settings_menu[] = array("menu_title"=>_l("Keys"),"link"=>site_url("setting/keys"),"menu_icon"=>"nav-icon fas fa-cog","badge"=>"");
             $menu_array[] = array("menu_title"=>_l("Settings"),"link"=>"javascript:;","menu_icon"=>"nav-icon fas fa-cog","badge"=>"","sub_menu"=>$settings_menu); 
            
            $active_menu = array();
            if(isset($active_menu_link)){
                $active_menu = $active_menu_link;
            }
            foreach($menu_array as $menu){
                create_menu($menu,$active_menu);
            }
            function create_menu($menu,$active = array()){
                $treemenu = "";
                if(isset($menu["sub_menu"])){
                    $treemenu = "has-treeview";
                }
                $class_active = "";
                if(isset($menu["link"]) && $menu["link"] != "" && $menu["link"] == current_url()){
                    $class_active = "active";
                }
                foreach($active as $act){
                    if(isset($menu["link"]) && $act == $menu["link"]){
                        $class_active = "active";
                    }
                }
                
                if(isset($menu["sub_menu"]) && !empty($menu["sub_menu"])){
                    foreach($menu["sub_menu"] as $sub_menu){
                        if(isset($sub_menu["link"]) && current_url() == $sub_menu["link"]){
                            $class_active = "active";
                        }else{
                            foreach($active as $act){
                                if(isset($sub_menu["link"]) && $act == $sub_menu["link"]){
                                    $class_active = "active";
                                }
                            }
                        }       
                    }
                }
                if($treemenu != "" && $class_active == "active"){
                    $treemenu = $treemenu." menu-open";
                }
                $draw_menu = '<li class="nav-item '.$treemenu.' '.$class_active.'">';
                $link = "javascript:;";
                if(isset($menu["link"]) && $menu["link"] != "")
                    $link = $menu["link"];
                $draw_menu .= '<a href="'.$link.'" class="nav-link '.$class_active.'">';
                if(isset($menu["menu_icon"]) && $menu["menu_icon"] != ""){
                    $draw_menu .= '<i class="'.$menu["menu_icon"].'"></i>';
                }
                $draw_menu .= '<p>'._l($menu["menu_title"]);
                if(isset($menu["badge"]) && $menu["badge"] != ""){
                    $draw_menu .=  '<span class="badge badge-info right">'.$menu["badge"].'
                                    </span>';
                }else if(isset($menu["sub_menu"]) && !empty($menu["sub_menu"])){
                    $draw_menu .=   '<i class="fas fa-angle-left right"></i>';
                }
                $draw_menu .= '</p></a>';
                echo $draw_menu;
                    if(isset($menu["sub_menu"]) && !empty($menu["sub_menu"])){
                        echo '<ul class="nav nav-treeview">';
                        foreach($menu["sub_menu"] as $sub_menu){
                            create_menu($sub_menu,$active);
                        }
                        echo '</ul>';
                    }
                echo '</li>';

            }
        ?>
        
        </ul>
      </nav>      
      
      </div>
      
    </aside>
 

