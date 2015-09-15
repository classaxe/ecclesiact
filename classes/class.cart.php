<?php
define ("VERSION_CART","1.0.7");
/*
Version History:
  1.0.7 (2015-09-13)
    1) References to Page::push_content() now changed to Output::push()

*/

class Cart {
  public static function draw_cart_controls($safeID,$productID,$quantity,$offset,$cart_skin=false,$cart_classname=''){
    if ($cart_skin){
      return Cart::draw_cart_controls_skin($safeID,$productID,$quantity,$offset,$cart_skin,$cart_classname);
    }
    return Cart::draw_cart_controls_noskin($safeID,$productID,$quantity,$offset);
  }

  public static function draw_cart_controls_noskin($safeID,$productID,$quantity,$offset){
    $field =    "cart_".$productID;
    $value =    $quantity;
    Output::push('javascript_onload',"  afb('".$field."','qty','');");
    return
       "<div class='cart_noskin'><input id=\"".$field."\" name=\"".$field."\" type=\"text\""
      ." value=\"".$value."\" class=\"formField\" size='2'"
      ." onchange=\"".$safeID."_cart(".$productID.",this.value,".$offset.")\" />\n"
      ."<div class='fl' style='width:11px;'>\n"
      ."  <img id=\"".$field."_up\" alt='+' src=\"".BASE_PATH."img/spacer\" class='icons cart_up' />\n"
      ."  <img id=\"".$field."_down\" alt='-' src=\"".BASE_PATH."img/spacer\" class='icons cart_down' />\n"
      ."</div>\n"
      ."<a class='fl' href=\"#\""
      ." onclick=\"geid_set('cart_".$productID."',".($quantity!=0 ? 0 : 1).");return geid('cart_".$productID."').onchange();\">"
      .($quantity!=0 ?
           "<img class='icons cart_items' alt='' src='".BASE_PATH."img/spacer'"
          ." title=\"Cart has ".$quantity." of this item \nUse +/- or enter a new value to change \nor click cart to remove the item(s)\" />"
       :
         "<img class='icons cart_noitems' alt='' src='".BASE_PATH."img/spacer'"
        ." title=\"Cart has none of this item\nClick cart or '+' to add it\" />"
       )
       ."</a>\n"
       ."</div>";
  }
  public static function draw_cart_controls_skin($safeID,$productID,$quantity,$offset,$cart_skin,$cart_classname){
    $field =    "cart_".$productID;
    $value =    $quantity;
    Cart::_draw_cart_controls_skin_css($cart_skin);
    Output::push('javascript_onload',"  afb('".$field."','cart','');");
    return
       "<div class='cart_skin'>\n"
      ."  <img id=\"".$field."_up\" alt='+' src=\"".BASE_PATH."img/spacer\" class='cart_skin_bg cart_up' />\n"
      .($quantity!=0 ?
         "  <div class='cart_skin_bg cart_items'"
        ."    title=\"Cart has ".$quantity." of this item \nUse +/- or enter a new value to change \nor click cart to remove the item(s)\">"
       :
         "<div class='cart_skin_bg cart_noitems'"
        ." title=\"Cart has none of this item\nClick cart or '+' to add it\">"
       )
      ."    <input id=\"".$field."\" name=\"".$field."\" type=\"text\""
      ."     value=\"".$value."\" size='2' class='cart_input'"
      ."     onchange=\"".$safeID."_cart(".$productID.",this.value,".$offset.")\" />\n"
      ."    <a class='fl' href=\"#\""
      ." onclick=\"geid_set('cart_".$productID."',".($quantity!=0 ? 0 : 1).");return geid('cart_".$productID."').onchange();\">"
      ."<img src=\"".BASE_PATH."img/spacer\" class='cart_buy' alt='' /></a>\n"
      ."  </div>\n"
      ."  <img id=\"".$field."_down\" alt='-' src=\"".BASE_PATH."img/spacer\" class='cart_skin_bg cart_down' />\n"
      ."</div>";
  }

  protected static function _draw_cart_controls_skin_css($cart_skin){
    static $shown = false;
    if (!$shown){
      $css =
         ".cart_skin_bg {\n"
        ."  background-image:url(".BASE_PATH.trim($cart_skin,"./").");\n"
        ."}\n";
      Output::push('style',$css);
      $shown = true;
    }
  }

  public static function empty_cart(){
    if (isset($_SESSION['cart_items'])) {
      $_SESSION['cart_items'] = array();
    }
  }

  public static function get_items(){
    $out = array();
    foreach ($_SESSION['cart_items'] AS $key=>$qty){
      $key_arr = explode('|',$key);
      if ($key_arr[0]){
        $out[] = array(
          'ID' =>                 $key_arr[0],
          'related_object' =>     (isset($key_arr[1]) ? $key_arr[1] : ""),
          'related_objectID' =>   (isset($key_arr[2]) ? $key_arr[2] : ""),
          'qty' =>                $qty
        );
      }
    }
    return $out;
  }

  public static function has_items(){
//    y($_SESSION['cart_items']);
    return (isset($_SESSION['cart_items']) && count($_SESSION['cart_items'])>0);
  }

  public static function initialise(){
    if (!isset($_SESSION['cart_items'])) {
      $_SESSION['cart_items'] = array();
    }
  }

  public static function item_convert_to_pending($productID, $related_object='', $related_objectID=''){
    $key =  $productID.'|'.$related_object.'|'.$related_objectID;
    $_SESSION['pending_cart_items'][$key] = $_SESSION['cart_items'][$key];
    unset($_SESSION['cart_items'][$key]);
  }

  public static function item_get_quantity($productID, $related_object='', $related_objectID=''){
    $key =  $productID.'|'.$related_object.'|'.$related_objectID;
    if (!isset($_SESSION['cart_items'][$key])){
      return 0;
    }
    return $_SESSION['cart_items'][$key];
  }

  public static function item_remove($productID, $related_object='', $related_objectID=''){
    $key =  $productID.'|'.$related_object.'|'.$related_objectID;
    unset($_SESSION['cart_items'][$key]);
  }

  public static function item_set_quantity($productID, $qty, $related_object='', $related_objectID=''){
    $key =  $productID.'|'.$related_object.'|'.$related_objectID;
    $_SESSION['cart_items'][$key] = (int)$qty;
  }

  public static function pending_order_get_ID(){
    if (!isset($_SESSION['pending_order'])){
      return false;
    }
    return $_SESSION['pending_order'];
  }

  public static function pending_order_set_ID($ID){
    $_SESSION['pending_order'] = $ID;
  }

  public static function pending_order_unset_ID(){
    unset($_SESSION['pending_order']);
  }

  public static function repopulate_all_from_pending(){
    if (isset($_SESSION['pending_cart_items'])) {
      foreach ($_SESSION['pending_cart_items'] as $key => $qty){
        $key_arr = explode('|',$key);
        Cart::item_set_quantity($key_arr[0], $qty, $key_arr[1], $key_arr[2]);
      }
      unset($_SESSION['pending_cart_items']);
    }
  }

  public static function update_cart(){
    global $page_vars;
    if (!get_var('targetID')) {
      return;
    }
    $key = get_var('targetID')."|".get_var('source');
    if ((int)get_var('targetValue')<1) {
      unset($_SESSION['cart_items'][$key]);
    }
    else {
      $_SESSION['cart_items'][$key] = (int)get_var('targetValue');
    }
    header(
      "Location: ".Page::get_URL($page_vars)
     ."?rnd=".dechex(mt_rand(0,mt_getrandmax()))
     .get_var('anchor')
    );
    print "";die();
  }

  public static function getVersion(){
    return VERSION_CART;
  }
}
?>