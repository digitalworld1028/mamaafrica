<?php class Cart_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'cart';
        $this->primary_key= 'cart_id';
    }
    function get_cart_items($user_id,$product_id=""){
        $this->db->distinct();
        $this->db->select("cart.*,products.product_name_en,products.product_name_ar,products.price,products.price_note,product_discounts.discount,product_discounts.discount_type,product_discounts.product_discount_id,products.product_image,products.is_promotional,products.is_veg,products.calories");
        $this->db->join("products","products.product_id = cart.product_id");
        $this->db->join("product_discounts","product_discounts.product_id = ".$this->table_name.".product_id and product_discounts.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.status = 1 and product_discounts.draft = 0","left");
      
        $this->db->where("cart.user_id",$user_id);
        $this->db->where("products.draft","0");
        if($product_id != ""){
            $this->db->where("products.product_id",$product_id);
        }
        $this->db->where("cart.draft","0");
        $this->db->order_by("cart_id");
        $q = $this->db->get("cart");
        return $q->result();
    }
    function get_cart_items_options($product_id,$user_id){
        $this->db->where("cart_option.product_id",$product_id);
        $this->db->where("cart_option.user_id",$user_id);
        $this->db->join("product_options","product_options.product_option_id = cart_option.product_option_id");
        $q = $this->db->get("cart_option");
        return $q->result();

    }
    function manage_cart($user_id,$product_id="")
    {
        $order_items = $this->get_cart_items($user_id,$product_id);

        $total_price = 0;
        $final_price = 0;
            
        foreach ($order_items as $product) {
            $product_price = $product->price;
            $total_price = $total_price + ($product_price * $product->qty);

            $product->effected_price = $product_price;
            $discount_amount = 0;
            if ($product->discount != null && $product->discount > 0) {
                if ($product->discount_type == "flat") {
                    $discount_amount = $product->discount;
                    $product_price = $product_price - $product->discount;
                } elseif ($product->discount_type == "percentage") {
                    $discount_amount = $product->discount * $product_price  / 100;
                    $product_price = $product_price - $discount_amount;
                }
                $product->effected_price = $product_price;
            }
            $product->discount_amount = $discount_amount;
            
            $product_price = $product_price * $product->qty;
            $final_price = $final_price + $product_price;

            $cart_price = $product_price;
            $product_options = $this->get_cart_items_options($product->product_id,$user_id);
            foreach($product_options as $option){
                $final_price = $final_price + ($option->price * $option->qty);
                $total_price = $total_price + ($option->price * $option->qty);
                $cart_price = $cart_price + ($option->price * $option->qty);
            }
            $product->cart_price = $cart_price;
            $product->product_options = $product_options;
        }

        return array("products"=>$order_items,"net_paid_amount"=>$final_price,"total_amount"=>$total_price);
    }
}
