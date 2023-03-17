<?php

use Illuminate\Support\Facades\Route;

//Country
Route::post('/customer/country', [App\Http\Controllers\Api\Customer\OrderController::class, 'get_country']);
Route::post('/customer/state', [App\Http\Controllers\Api\Customer\OrderController::class, 'get_state']);
Route::post('/customer/city', [App\Http\Controllers\Api\Customer\OrderController::class, 'get_city']);

Route::post('/customer/country/find-by-name', [App\Http\Controllers\Api\Customer\OrderController::class, 'findCountryByName']);
Route::post('/customer/state/find-by-name', [App\Http\Controllers\Api\Customer\OrderController::class, 'findStateByName']);
Route::post('/customer/city/find-by-name', [App\Http\Controllers\Api\Customer\OrderController::class, 'findCityByName']);

Route::post('/customer/register', [App\Http\Controllers\Api\Customer\CustomerAuth_Api::class, 'register']);
Route::post('/customer/login', [App\Http\Controllers\Api\Customer\CustomerAuth_Api::class, 'login']);
Route::post('/customer/social/login', [App\Http\Controllers\Api\Customer\CustomerAuth_Api::class, 'socialLogin']);
Route::post('/customer/verify/otp', [App\Http\Controllers\Api\Customer\CustomerAuth_Api::class, 'verifyOtp']);
Route::post('/customer/add-cart-minimum-quantity', [App\Http\Controllers\Api\Customer\InsertResponse::class, 'minimum_quantity']);
Route::post('/customer/home', [App\Http\Controllers\Api\Customer\Homepage::class, 'index']);
Route::post('/customer/most-search', [App\Http\Controllers\Api\Customer\Homepage::class, 'most_searched']);
Route::post('/customer/product-category', [App\Http\Controllers\Api\Customer\ProductController::class, 'product_by_category']);
Route::post('/customer/product-subcategory', [App\Http\Controllers\Api\Customer\ProductController::class, 'product_by_subcategory']);
Route::post('/customer/product-brand', [App\Http\Controllers\Api\Customer\ProductController::class, 'product_by_brand']);
Route::post('/customer/product-detail', [App\Http\Controllers\Api\Customer\ProductController::class, 'product_detail']);
Route::post('/customer/product-dailydeal', [App\Http\Controllers\Api\Customer\ProductController::class, 'daily_deal']);
Route::post('/customer/shock-sale', [App\Http\Controllers\Api\Customer\ProductController::class, 'shocking_sale']);
Route::post('/customer/auction', [App\Http\Controllers\Api\Customer\ProductController::class, 'auction_product']);
Route::post('/customer/shop-detail', [App\Http\Controllers\Api\Customer\ProductController::class, 'shop_detail']);
Route::post('/customer/shop-product-search', [App\Http\Controllers\Api\Customer\ProductController::class, 'shop_product_search']);
Route::post('/customer/filter', [App\Http\Controllers\Api\Customer\Homepage::class, 'filter_on_search']);
//Route::post('/customer/product-search', [App\Http\Controllers\Api\Customer\Homepage::class, 'product_search']);
Route::post('/customer/cat-subcat', [App\Http\Controllers\Api\Customer\Homepage::class, 'category_subcategory']);
Route::post('/customer/subcategory', [App\Http\Controllers\Api\Customer\Homepage::class, 'get_subcategory_list']);
Route::post('/customer/brand', [App\Http\Controllers\Api\Customer\Homepage::class, 'brands']);
Route::post('/customer/product-list', [App\Http\Controllers\Api\Customer\ProductController::class, 'product_list']);
Route::post('/customer/shock-sale-products', [App\Http\Controllers\Api\Customer\Homepage::class, 'shocking_sale_products']);
Route::post('/customer/product-list-filter', [App\Http\Controllers\Api\Customer\ProductController::class, 'product_list_filter']);
Route::post('/customer/product-featured', [App\Http\Controllers\Api\Customer\ProductController::class, 'featured_products']);
Route::post('/customer/product-deals', [App\Http\Controllers\Api\Customer\ProductController::class, 'daily_deals']);
Route::post('/customer/user-review', [App\Http\Controllers\Api\Customer\ProductController::class, 'customer_prd_revirew']);

Route::post('/customer/featured', [App\Http\Controllers\Api\Customer\Homepage::class, 'featured_prod']);

//filter
Route::post('/customer/products-latest', [App\Http\Controllers\Api\Customer\ProductController::class, 'product_list_filter_latest']);
Route::post('/customer/products-popular', [App\Http\Controllers\Api\Customer\ProductController::class, 'product_list_filter_popular']);
Route::post('/customer/products-low', [App\Http\Controllers\Api\Customer\ProductController::class, 'product_list_filter_low']);
Route::post('/customer/products-high', [App\Http\Controllers\Api\Customer\ProductController::class, 'product_list_filter_high']);
Route::post('/customer/product-search', [App\Http\Controllers\Api\Customer\ProductController::class, 'product_search']);


//otp
Route::post('/customer/register/send/otp', [App\Http\Controllers\Api\Customer\CustomerAuth_Api::class, 'regSendotp']);
Route::post('/customer/register/verify/otp', [App\Http\Controllers\Api\Customer\CustomerAuth_Api::class, 'regVerifyotp']);
Route::post('/customer/login/send/otp', [App\Http\Controllers\Api\Customer\CustomerAuth_Api::class, 'loginSendotp']);
Route::post('/customer/login/verify/otp', [App\Http\Controllers\Api\Customer\CustomerAuth_Api::class, 'loginVerifyotp']);

//otp email
Route::post('/customer/register/verify-email/otp', [App\Http\Controllers\Api\Customer\CustomerAuth_Api::class, 'regSendotpemail']);
Route::post('/customer/register/check-email/otp', [App\Http\Controllers\Api\Customer\CustomerAuth_Api::class, 'regVerifyotpemail']);
Route::post('/customer/login-email/send/otp', [App\Http\Controllers\Api\Customer\CustomerAuth_Api::class, 'loginSendotpemail']);
Route::post('/customer/login-email/verify/otp', [App\Http\Controllers\Api\Customer\CustomerAuth_Api::class, 'loginVerifyotpemail']);

//CART
Route::post('/customer/cart', [App\Http\Controllers\Api\Customer\CartController::class, 'index']);
Route::post('/customer/delete-cart', [App\Http\Controllers\Api\Customer\CartController::class, 'delete_cart']);
Route::post('/customer/cart/total', [App\Http\Controllers\Api\Customer\CartController::class, 'cart_total']);
Route::post('/customer/cart-count', [App\Http\Controllers\Api\Customer\CartController::class, 'cart_count']);
Route::post('/customer/cart-qty-by-product-id', [App\Http\Controllers\Api\Customer\InsertResponse::class, 'cart_qty_by_product_id']);
Route::post('/customer/delete-product-by-product-id', [App\Http\Controllers\Api\Customer\CartController::class, 'delete_cart_by_product_id']);

//Coupon
Route::post('/customer/cart/apply-coupon', [App\Http\Controllers\Api\Customer\CartController::class, 'apply_coupon']);

//Credit

Route::post('/customer/credits', [App\Http\Controllers\Api\Customer\CreditController::class, 'customer_credit']);
Route::post('/customer/credit-payment', [App\Http\Controllers\Api\Customer\CreditController::class, 'payment']);

//order
Route::post('/customer/order/placeorder', [App\Http\Controllers\Api\Customer\OrderController::class, 'placeorder']);
Route::post('/customer/order/checkout-info', [App\Http\Controllers\Api\Customer\OrderController::class, 'checkout_info_page']);
Route::post('/customer/order/track-order', [App\Http\Controllers\Api\Customer\OrderController::class, 'track_order']);
Route::post('/customer/order/order-history', [App\Http\Controllers\Api\Customer\OrderController::class, 'order_history']);
Route::post('/customer/order/invoice/pdf', [App\Http\Controllers\Api\Customer\OrderController::class, 'order_invoice']);


//BuyNow
Route::post('/customer/buynow/view', [App\Http\Controllers\Api\Customer\BuynowController::class, 'view']);
Route::post('/customer/buynow/placeorder', [App\Http\Controllers\Api\Customer\BuynowController::class, 'buynow']);

//Support chat
Route::post('/customer/create-ticket', [App\Http\Controllers\Api\Customer\SupportCustomer::class, 'create_ticket']);
Route::post('/customer/list-ticket', [App\Http\Controllers\Api\Customer\SupportCustomer::class, 'ticket_list']);
Route::post('/customer/add-ticket-message', [App\Http\Controllers\Api\Customer\SupportCustomer::class, 'add_message']);
Route::post('/customer/support-message', [App\Http\Controllers\Api\Customer\SupportCustomer::class, 'view_message']);

//Seller customer CHAT
Route::post('/customer/chat/send', [App\Http\Controllers\Api\Customer\ChatsController::class, 'send_message']);
Route::post('/customer/chat/list', [App\Http\Controllers\Api\Customer\ChatsController::class, 'list']);
Route::post('/customer/chat/message', [App\Http\Controllers\Api\Customer\ChatsController::class, 'chat_message']);
Route::post('/customer/chat-count', [App\Http\Controllers\Api\Customer\ChatsController::class, 'chat_count']);


//voucher
Route::post('/customer/coupon-list', [App\Http\Controllers\Api\Customer\CartController::class, 'coupon_list']);
Route::post('/customer/coupon/seller', [App\Http\Controllers\Api\Customer\VoucherController::class, 'seller_voucher']);
Route::post('/customer/coupon/platform', [App\Http\Controllers\Api\Customer\VoucherController::class, 'admin_voucher']);

//currency
Route::post('/customer/currency', [App\Http\Controllers\Api\Customer\Homepage::class, 'currency']);


/*****************INSERT RESPONSES**************************/
Route::post('/customer/post-seller-review', [App\Http\Controllers\Api\Customer\InsertResponse::class, 'insert_seller_review']);
Route::post('/customer/create-bid', [App\Http\Controllers\Api\Customer\InsertResponse::class, 'add_bid']);
//edited
Route::post('/customer/product/post-product-review', [App\Http\Controllers\Api\Customer\InsertResponse::class, 'insert_product_review']);
Route::post('/customer/add-cart', [App\Http\Controllers\Api\Customer\InsertResponse::class, 'insert_cart']);
Route::post('/customer/cart/change-qty', [App\Http\Controllers\Api\Customer\InsertResponse::class, 'change_cart_qty']);
Route::post('/customer/add-wishlist', [App\Http\Controllers\Api\Customer\InsertResponse::class, 'insert_wishlist']);
Route::post('/customer/notification/update', [App\Http\Controllers\Api\Customer\InsertResponse::class, 'notify_view_update']);



/*****************MY ACCOUNT**************************/
Route::post('/customer/my-business-details', [App\Http\Controllers\Api\Customer\AccountController::class, 'my_business_details']);
Route::post('/customer/view-my-business-details', [App\Http\Controllers\Api\Customer\AccountController::class, 'view_my_business_details']);
Route::post('/customer/purchase_filter', [App\Http\Controllers\Api\Customer\AccountController::class, 'my_purchase_filter']);
Route::post('/customer/addresstype', [App\Http\Controllers\Api\Customer\AccountController::class, 'addresstype']);
Route::post('/customer/add/wishlist', [App\Http\Controllers\Api\Customer\AccountController::class, 'addWishlist']);
Route::post('/customer/wishlist', [App\Http\Controllers\Api\Customer\AccountController::class, 'wishlist']);
Route::post('/customer/remove/wishlist', [App\Http\Controllers\Api\Customer\AccountController::class, 'removeWishlist']);
Route::post('/customer/mypurchase', [App\Http\Controllers\Api\Customer\AccountController::class, 'purchase']);
Route::post('/customer/order/detail', [App\Http\Controllers\Api\Customer\AccountController::class, 'order_detail']);
Route::post('/customer/cancel/request', [App\Http\Controllers\Api\Customer\AccountController::class, 'cancel_request']);
Route::post('/customer/cancel/request/list/seller', [App\Http\Controllers\Api\Customer\AccountController::class, 'seller_req_list']);
Route::post('/customer/cancel/request/list/customer', [App\Http\Controllers\Api\Customer\AccountController::class, 'cust_req_list']);
Route::post('/customer/past/cancel/list/seller', [App\Http\Controllers\Api\Customer\AccountController::class, 'seller_past_list']);
Route::post('/customer/past/cancel/list/customer', [App\Http\Controllers\Api\Customer\AccountController::class, 'cust_past_list']);
Route::post('/customer/response/cancel/request', [App\Http\Controllers\Api\Customer\AccountController::class, 'response_request']);
Route::post('/customer/profile', [App\Http\Controllers\Api\Customer\AccountController::class, 'get_profile']);
Route::post('/customer/edit/profile', [App\Http\Controllers\Api\Customer\AccountController::class, 'edit_profile']);
Route::post('/customer/address', [App\Http\Controllers\Api\Customer\AccountController::class, 'userAddress']);
Route::post('/customer/add/address', [App\Http\Controllers\Api\Customer\AccountController::class, 'addAddress']);
Route::post('/customer/edit/address', [App\Http\Controllers\Api\Customer\AccountController::class, 'editAddress']);
Route::post('/customer/remove/address', [App\Http\Controllers\Api\Customer\AccountController::class, 'deleteAddress']);
Route::post('/customer/default/address', [App\Http\Controllers\Api\Customer\AccountController::class, 'defaultAddress']);
Route::post('/customer/logout', [App\Http\Controllers\Api\Customer\AccountController::class, 'logout']);
//Route::post('/customer/return/request', [App\Http\Controllers\Api\Customer\AccountController::class, 'return_request']);
Route::post('/customer/usage/coupon', [App\Http\Controllers\Api\Customer\AccountController::class, 'usageCoupon']);
Route::post('/customer/recent/views', [App\Http\Controllers\Api\Customer\AccountController::class, 'recent_views']);
Route::post('/customer/wallet/amount', [App\Http\Controllers\Api\Customer\AccountController::class, 'wallet_amount']);
Route::post('/customer/view/notifications', [App\Http\Controllers\Api\Customer\AccountController::class, 'notifications']);
Route::post('/customer/order/invoice', [App\Http\Controllers\Api\Customer\AccountController::class, 'invoice']);
//Route::post('/customer/order/return/shipment', [App\Http\Controllers\Api\Customer\AccountController::class, 'return_shipment']);


Route::post('/customer/existingpwd-change', [App\Http\Controllers\Api\Customer\AccountController::class, 'exist_pwd_change']);

/*****************AUCTION**************************/
Route::post('/customer/auction/detail', [App\Http\Controllers\Api\Customer\AuctionController::class, 'auction_detail']);
Route::post('/customer/auction/checkout', [App\Http\Controllers\Api\Customer\AuctionController::class, 'auction_checkout']);
Route::post('/customer/auction/order/list', [App\Http\Controllers\Api\Customer\AuctionController::class, 'auction_order_list']);

// STRIPE KEYS
Route::post('/customer/stripe-keys', [App\Http\Controllers\Api\Customer\StripeController::class, 'stripeKeys'])->name('stripe.keys');

/*****************FORGOT PASSWORD**************************/
Route::post('/customer/forgot/password', [App\Http\Controllers\Api\Auth\LoginController::class, 'forgotPassword']);

// Invite & Save

Route::post('/customer/invite-save', [App\Http\Controllers\Api\Customer\AccountController::class, 'invite_save']);



//RETURN
Route::post('/customer/return/reasons', [App\Http\Controllers\Api\Customer\OrderReturnController::class, 'reasons']);
Route::post('/customer/return-and-refund/send-request', [App\Http\Controllers\Api\Customer\OrderReturnController::class, 'refund_request']);
Route::post('/customer/return/confirm-shipment', [App\Http\Controllers\Api\Customer\OrderReturnController::class, 'return_shipment']);
Route::post('/customer/return-and-replace/send-request', [App\Http\Controllers\Api\Customer\OrderReturnController::class, 'replace_request']);

//My coupon
Route::post('/customer/my-coupon/list', [App\Http\Controllers\Api\Customer\VoucherController::class, 'my_coupon']);
//Offers
Route::post('/customer/offer/list', [App\Http\Controllers\Api\Customer\VoucherController::class, 'offerlist']);

//language
Route::post('/customer/language', [App\Http\Controllers\Api\Customer\GeneralController::class, 'language_list']);
//label
Route::post('/customer/label/list', [App\Http\Controllers\Api\Customer\GeneralController::class, 'label_list']);

// Customer Credits

Route::post('/customer/credits-list', [App\Http\Controllers\Api\Customer\CreditsController::class, 'listing']);
Route::post('/customer/credits-payment', [App\Http\Controllers\Api\Customer\CreditsController::class, 'payment']);
Route::post('/customer/referral', [App\Http\Controllers\Api\Customer\AccountController::class, 'referral']);


//Branches
Route::post('/customer/branches', [App\Http\Controllers\Api\Customer\AccountController::class, 'branch_list']);
Route::post('/customer/add-branch', [App\Http\Controllers\Api\Customer\AccountController::class, 'add_branch']);
Route::post('/customer/update-branch', [App\Http\Controllers\Api\Customer\AccountController::class, 'update_branch']);
Route::post('/customer/branch-employees', [App\Http\Controllers\Api\Customer\AccountController::class, 'branch_employees']);
Route::post('/customer/add-employee', [App\Http\Controllers\Api\Customer\AccountController::class, 'add_employee']);
Route::post('/customer/update-employee', [App\Http\Controllers\Api\Customer\AccountController::class, 'update_employee']);
Route::post('/customer/delete-employee', [App\Http\Controllers\Api\Customer\AccountController::class, 'delete_employee']);
Route::post('/customer/delete-branch', [App\Http\Controllers\Api\Customer\AccountController::class, 'delete_branch']);
//Route::post('/customer/webhooks/stripe', [App\Http\Controllers\Api\Customer\StripeWebhookController::class, 'handleWebhook']);


//forgot password
Route::post('/customer/login/forgot/password', [App\Http\Controllers\Api\Customer\CustomerAuth_Api::class, 'forgotPassword']);
Route::post('/customer/login/forgot/verify-otp', [App\Http\Controllers\Api\Customer\CustomerAuth_Api::class, 'password_verifyotp']);
Route::post('/customer/password/change', [App\Http\Controllers\Api\Customer\CustomerAuth_Api::class, 'change_password']);

//Reward at register
Route::post('/customer/register/reward', [App\Http\Controllers\Api\Customer\CustomerAuth_Api::class, 'referralCode']);

//KYC
Route::post('/customer/register/kyc', [App\Http\Controllers\Api\Customer\CustomerAuth_Api::class, 'kycUpdate']);

//POINTS

Route::post('/customer/points/list', [App\Http\Controllers\Api\Customer\CustomerPointsController::class, 'point_list']);

// Route::post('/customer/points/list', [App\Http\Controllers\Api\Customer\LoyaltyPointController::class, 'point_list']);
Route::post('/customer/points/rewards', [App\Http\Controllers\Api\Customer\LoyaltyPointController::class, 'reward_list']);
Route::post('/customer/points/reward/redeem', [App\Http\Controllers\Api\Customer\LoyaltyPointController::class, 'reward_redeem']);

Route::post('/customer/beverage-products', [App\Http\Controllers\Api\Customer\ProductController::class, 'beverage_products']);
Route::post('/customer/fruit-veg-sub-list', [App\Http\Controllers\Api\Customer\Homepage::class, 'fruit_veg_sub_list']);
Route::post('/customer/explore-products', [App\Http\Controllers\Api\Customer\ProductController::class, 'explore_products']);
Route::post('/customer/trending-products', [App\Http\Controllers\Api\Customer\ProductController::class, 'trending_products']);
Route::post('/customer/shocking-sale', [App\Http\Controllers\Api\Customer\Homepage::class, 'shocking_sale']);

Route::post('/customer/profile-image/update', [App\Http\Controllers\Api\Customer\AccountController::class, 'prof_image']);

Route::post('/customer/home/banners', [App\Http\Controllers\Api\Customer\Homepage::class, 'home_banners']);
Route::post('/customer/home/coming-soon', [App\Http\Controllers\Api\Customer\Homepage::class, 'home_coming_soon']);
Route::post('/customer/home/daily-deals', [App\Http\Controllers\Api\Customer\Homepage::class, 'home_daily_deals']);
Route::post('/customer/home/trending', [App\Http\Controllers\Api\Customer\Homepage::class, 'home_trending_products']);
Route::post('/customer/home/featured', [App\Http\Controllers\Api\Customer\Homepage::class, 'home_featured']);
Route::post('/customer/home/explore', [App\Http\Controllers\Api\Customer\Homepage::class, 'home_explore']);
Route::post('/customer/home/stores', [App\Http\Controllers\Api\Customer\Homepage::class, 'home_stores']);
Route::post('/customer/home/occasion', [App\Http\Controllers\Api\Customer\Homepage::class, 'home_occasions']);


Route::post('/customer/products/stores-list', [App\Http\Controllers\Api\Customer\ProductController::class, 'stores_list']);
Route::post('/customer/products/price-split', [App\Http\Controllers\Api\Customer\ProductController::class, 'pricesplit_list']);
Route::post('/customer/products/occasion-list', [App\Http\Controllers\Api\Customer\ProductController::class, 'all_occasions']);
Route::post('/customer/products/coming-soon', [App\Http\Controllers\Api\Customer\ProductController::class, 'coming_soon']);
Route::post('/customer/products/daily-deals', [App\Http\Controllers\Api\Customer\ProductController::class, 'deals_all']);

//Payment_successful
Route::post('/customer/paymentsuccess', [App\Http\Controllers\Api\Customer\PaymentSuccessController::class, 'paymentsuccess']);

//Order Details
Route::post('/customer/paymentsuccess', [App\Http\Controllers\Api\Customer\PaymentSuccessController::class, 'paymentsuccess']);
