<?php defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Order extends REST_Controller
{
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model("orders_model");
    }
    public function list_post()
    {
        $user_id = $this->post("user_id");
        if ($user_id == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("User referance required"),
                DATA =>_l("User referance required"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }
        
        $orders = $this->orders_model->get(array("orders.user_id"=>$user_id));
        $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("You orders"),
                DATA => $orders,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
    }

    public function send_post()
    {
        $post = $this->post();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User Referance', 'trim|required');
        $this->form_validation->set_rules('paid_by', 'Payment Type', 'trim|required');
        $this->form_validation->set_rules('order_type', 'Order Type', 'trim|required');
        if ($this->form_validation->run() == false) {
            $this->response(array(
                        RESPONCE => false,
                        MESSAGE => strip_tags($this->form_validation->error_string()),
                        DATA =>strip_tags($this->form_validation->error_string()),
                        CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        } else {
            $user_id = $post["user_id"];
            $paid_by = $post["paid_by"];
            $order_type = $post["order_type"];

            $branch_id = (isset($post["branch_id"])) ? $post["branch_id"] : "0";
            $user_address_id = (isset($post["user_address_id"])) ? $post["user_address_id"] : "0";
            $coupon_code = $post["coupon_code"];
            $order_note = $post["order_note"];

            $order_date = date(MYSQL_DATE_FORMATE);
                        
            $this->db->select("Max(order_id) as max_id");
            $q = $this->db->get("orders");
            $max_order = $q->row();
            $order_no = $max_order->max_id + 1;

            $this->load->model("cart_model");
            $cart = $this->cart_model->manage_cart($user_id);

            $order_products= $cart["products"];

            if ($order_products == null || empty($order_products)) {
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Something wrong in item inputs"),
                    DATA =>_l("Something wrong in item inputs"),
                    CODE => 100
                ), REST_Controller::HTTP_OK);
            }

            // Add Order Items
            // items are in json array [{product_id : 1, order_qty : 1, }]
            $this->load->model("products_model");
                        
            $net_amount = $cart["net_paid_amount"];
            $total_order_amount = $cart["total_amount"];
            $final_discount = $total_order_amount - $net_amount;
            // Validate Coupon First
            $coupon_responce = array();
            if ($coupon_code != null || $coupon_code != "") {
                $this->load->model("coupons_model");
                $coupon_responce = $this->coupons_model->validate($user_id, $coupon_code);
                if (!$coupon_responce[RESPONCE]) {
                    $this->response($coupon_responce, REST_Controller::HTTP_OK);
                }
            }

            // Applu Coupon on Total Amount if applicable
            
            $order_discount = 0;
            $order_discount_type = "";
            $order_discount_amount = 0;
            if (!empty($coupon_responce) && $coupon_responce[RESPONCE]) {
                $coupon = (Object)$coupon_responce[DATA];
                if (!empty($coupon)) {
                    if ($total_order_amount < $coupon->min_order_amount) {
                        $this->response(array(
                            RESPONCE => false,
                            MESSAGE => _l("Discount coupon is not applicable, Please try with min order amount ".$coupon->min_order_amount),
                            DATA =>_l("Discount coupon is not applicable, Please try with min order amount ".$coupon->min_order_amount),
                            CODE => 101
                        ), REST_Controller::HTTP_OK);
                    } else {
                        if ($coupon->discount_type == "flat") {
                            $order_discount_amount = $coupon->discount;
                        } elseif ($coupon->discount_type == "percentage") {
                            $order_discount_amount = $coupon->discount * $net_amount  / 100;
                        }
                        if ($order_discount_amount > $coupon->max_discount_amount) {
                            $order_discount_amount = $coupon->max_discount_amount;
                        }
                        $net_amount = $net_amount - $order_discount_amount;
                        $order_discount_type = $coupon->discount_type;
                        $order_discount = $coupon->discount;
                    }
                }
            }
            // Initial order insert
            $order_status = ORDER_PENDING;
            $gateway_charges = 0;
            if ($paid_by != "cod") {
                $order_status = ORDER_UNPAID;
                $gateway_charges = get_option("gateway_charges");
                
            }
            $delivery_charges = 0;
            $site_options = get_options(array("delivery_charge","currency_symbol"));
            if ($user_address_id != "0" && $order_type == "delivery") {
                $delivery_charges = $site_options["delivery_charge"];
            }

            $net_amount = $net_amount + $gateway_charges + $delivery_charges;
            $order_discount_amount = $order_discount_amount + $final_discount;
            
            $order_init = array(
                "order_no"=>$order_no,
                "order_date"=>date(MYSQL_DATE_TIME_FORMATE),
                "user_id"=>$user_id,
                "branch_id"=>$branch_id,
                "user_address_id"=>$user_address_id,
                "order_note"=>$order_note,
                "coupon_code"=>$coupon_code,
                "discount"=>$order_discount,
                "discount_type"=>$order_discount_type,
                "discount_amount"=>$order_discount_amount,
                "order_amount"=>$total_order_amount,
                "net_amount"=>$net_amount,
                "status"=>$order_status,
                "paid_by"=>$paid_by,
                "gateway_charges"=>$gateway_charges,
                "delivery_amount"=>$delivery_charges,
                "order_type"=>$order_type
            );
            $order_id = $this->common_model->data_insert("orders", $order_init, true);
            
            $this->common_model->data_insert("order_status", array("status"=>$order_status,"order_id"=>$order_id), true);

            foreach ($order_products as $item) {
                $order_item = array(
                    "order_id"=>$order_id,
                    "product_id"=>$item->product_id,
                    "order_qty"=>$item->qty,
                    "product_price"=>$item->price,
                    "discount_id"=>($item->product_discount_id == null) ? 0 : $item->product_discount_id,
                    "discount_amount"=>($item->discount_amount == null) ? 0 : $item->discount_amount,
                    "discount"=>($item->discount == null) ? 0 : $item->discount,
                    "discount_type"=>($item->discount_type == null) ? "" : $item->discount_type,
                    "price"=>$item->effected_price
                );
                $order_item_id = $this->common_model->data_insert("order_items", $order_item, false, false);
                foreach ($item->product_options as $option) {
                    $option_array = array(
                        "order_item_id"=>$order_item_id,
                        "order_id"=>$order_id,
                        "product_id"=>$item->product_id,
                        "product_option_id"=>$option->product_option_id,
                        "order_qty"=>$option->qty,
                        "option_price"=>$option->price,
                        "price"=>$option->price
                    );
                    $this->common_model->data_insert("order_item_options", $option_array, false, false);
                }
            }
            
            
            
            if ($paid_by == "cod") {
                // Flush Cart
                $this->order_response($order_id);
            } else {
                $this->load->library("payment_lib");
                $pay_ref = $this->payment_lib->doPayment($order_id, $net_amount);
                if ($pay_ref["response"]) {
                    $this->db->update("orders", array("payment_ref"=>$pay_ref["payment_ref"]), array("order_id"=>$order_id));
                    $this->response(array(
                        RESPONCE => true,
                        MESSAGE => $pay_ref["redirect_url"],
                        DATA => array("responseURL"=>$pay_ref["redirect_url"]),
                        CODE => CODE_SUCCESS
                    ), REST_Controller::HTTP_OK);
                } else {
                    $this->db->where("order_id", $order_id);
                    $this->db->delete("orders");

                    $this->db->where("order_id", $order_id);
                    $this->db->delete("order_items");

                    $this->db->where("order_id", $order_id);
                    $this->db->delete("order_delivery_address");

                    $this->response(array(
                        RESPONCE => false,
                        MESSAGE => _l("Sorry failed to make payment"),
                        DATA =>_l("Sorry failed to make payment"),
                        CODE => 101
                    ), REST_Controller::HTTP_OK);
                }
            }
        }
    }
    public function failedpayment_get()
    {
        $token = $this->get("token");
        if ($token == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Sorry failed to get payment token"),
                DATA =>_l("Sorry failed to get payment token"),
                CODE => 101
            ), REST_Controller::HTTP_OK);
        }
        $order = $this->orders_model->get_by_id("", $token);
        $order_id = $order->order_id;
        $this->db->where("order_id", $order_id);
        $this->db->delete("orders");

        $this->db->where("order_id", $order_id);
        $this->db->delete("order_items");

        $this->db->where("order_id", $order_id);
        $this->db->delete("order_delivery_address");

        $this->response(array(
                        RESPONCE => false,
                        MESSAGE => _l("Sorry failed to make payment"),
                        DATA =>_l("Sorry failed to make payment"),
                        CODE => 101
                    ), REST_Controller::HTTP_OK);
    }
    public function successpayment_get()
    {
        $token = $this->get("token");
        if ($token == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Sorry failed to get payment token"),
                DATA =>_l("Sorry failed to get payment token"),
                CODE => 101
            ), REST_Controller::HTTP_OK);
        }
        $this->order_response("", $token);
    }
    private function order_response($order_id, $pay_ref="")
    {
        $order = $this->orders_model->get_by_id($order_id, $pay_ref);
        $order_items = $this->orders_model->get_order_items($order->order_id);
        $order->items = $order_items;
        $user_id = $order->user_id;

        $this->db->where("user_id", $user_id);
        $this->db->delete("cart");

        $this->db->where("user_id", $user_id);
        $this->db->delete("cart_option");
            
        $this->load->model("email_model");
         
        foreach ($order_items as $item) {
            $option_items = $this->orders_model->get_order_option_items($item->order_item_id);
            $item->option_items = $option_items;
        }
        $this->email_model->send_order_mail($order, $order_items);
        $this->email_model->new_order_mail($order, $order_items);
            
        $msg = _l("Thanks for your order, Order with No #order_no# amount #net_amount# is placed successfully");
        $msg = str_replace(array("#order_no#","#net_amount#"), array($order->order_no,$order->net_amount), $msg);
        $this->response(array(
                    RESPONCE => true,
                    MESSAGE => $msg,
                    DATA => $order,
                    CODE => CODE_SUCCESS
                ), REST_Controller::HTTP_OK);
    }
    public function track_post()
    {
        $user_id = $this->post("user_id");
        if ($user_id == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please Provide User Referance"),
                DATA =>_l("Please Provide User Referance"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        $order = $this->orders_model->get(array("orders.user_id"=>$user_id, "in"=>array("orders.status"=>array(
            ORDER_PENDING, ORDER_CONFIRMED, ORDER_OUT_OF_DELIVEY, ORDER_DELIVERED
        )),"DATE(orders.order_date) >="=>date(MYSQL_DATE_FORMATE)));
        foreach ($order as $o) {
            $o->order_status = $this->orders_model->get_status($o->order_id);
        }
        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("Track Orders"),
            DATA => $order,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
    public function details_post()
    {
        $order_id = $this->post("order_id");
        $user_id = $this->post("user_id");
        if ($order_id == null || $user_id == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please Provide Order Referance"),
                DATA =>_l("Please Provide Order Referance"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        $order = $this->orders_model->get_by_id($order_id);
        $order_items = $this->orders_model->get_order_items($order_id);
        $order->items = $order_items;
        $order->order_status = $this->orders_model->get_status($order_id);
        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("Order Details"),
            DATA => $order,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
    public function cancel_post()
    {
        $order_id = $this->post("order_id");
        $user_id = $this->post("user_id");
        if ($order_id == null || $user_id == null) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please Provide Order Referance"),
                DATA =>_l("Please Provide Order Referance"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        $order = $this->orders_model->get_by_id($order_id);
        if ($order->status != ORDER_PENDING) {
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Sorry we can not cancel order, Because order in processing."),
                DATA =>_l("Sorry we can not cancel order, Because order in processing."),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        $this->common_model->data_update("orders", array("status"=>ORDER_CANCEL), array("order_id"=>$order_id));

        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("Order Details"),
            DATA => $order,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
}
