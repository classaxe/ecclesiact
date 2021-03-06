// 1.1.275
// nav_mouse(), img_state() and img_state_v() may be unused?
/*
Version History:
  1.0.275 (2020-10-24)
    1) Added support for fullscreen in video_setup()
*/

// ************************************
// * External References (for JSLint) *
// ************************************
/*jslint browser: true, evil: true, maxerr: 200 */

// Browser:
/*global alert, confirm, document, prompt, self, window, ActiveXObject, Components, Option */

// jquery
/*global $J */

// Inline code:
/*global base_url, currency_symbol, defaultTimeFormat, option_separator,
  pwd_len_min, rating_blocks, system_family, valid_prefix */

// Inline code when needed:
/*global destinationID_arr, cp_params, flowplayer, _global_date_range_min, _global_date_range_max,
doc, report_filters, report_filters_sort
*/

// Member.js:
/*global */

// Context Menu:
/*global CM_load, CM_visible, _CM, _CM_ID, _CM_text, _contextActive */

// Twitter:
/*global TWTR */


// ************************************
// * Function Declarations:           *
// ************************************
var addEvent, afb, ajax_div_loading, ajax_keytest, ajax_post, ajax_post_streamed,
  ajax_report, ap_registerPlayers, ap_stopAll, attach_field_behaviour,
  bugtracker_form, bugtracker_form_onsubmit, cal_events, cal_goto, cal_picker,
  cal_set_date, Calendar, calendar_changed_fn, calendar_changed_admin_fn,
  calendar_status_fn, calendar_tooltip_fn, calendar_large_setup,
  centerPopWin, char_counter, column_over, combo_selector_set,
  comment, comment_delete, comment_get_count, comment_mark, comment_show_all,
  community_member_embed, community_member_embed_code,
  cssAddJsonStyle, cssAttributeGet, cssAttributeSet, csv_item_set,
  cursorGetSelectionEnd, cursorGetSelectionStart, cursorIsAtEnd, cursorIsAtStart,
  cursorSetPosition, customform_draw_fees_overview, customform_get_tax_costs, customise_colours,
  date_selector_draw, date_selector_onchange, disableTabIndexes, displaySelectBoxes,
  div_toggle, document_reader, document_reader_get_page_name, document_reader_get_page_prefix,
  document_reader_goto_page, donate, EventCache, externalLinks,
  filterbar_clear_onclick, filterbar_disable_controls, filterbar_filterExact_onchange,
  filterbar_filterField_onchange, filterbar_go_onclick, filterbar_save_onclick,
  filterbar_value_onblur, filterbar_value_onclick, filterbar_value_onkeypress,
  flowplayer_popup, flowplayer_video, gallery_album_image_mouseover,
  gallery_album_image_mouseout, gallery_album_image_show, gallery_album_image_offset,
  gallery_album_slideshow, geid, geid_set, geid_val,
  get_taxes_applied, get_valid_name, goto_page, hidePopWin, hideSelectBoxes,
  image_rotator, img_over, img_state,
  img_state_v, include, initialise_tooltips, inline_signin_hide_msg, keyDownHandler,
  keytest, keytest_enter_execute, keytest_enter_transfer, language_chooser, language_set,
  lead_zero, list_set_onclick_items, list_set_onclick_items_fn,
  LTrim, makeReadOnly, match_picture, nav_setup, nav_setup_attach, nav_setup_sortable,
  nav_setup_fix_mouseovers, nav_setup_state, nav_mouse, obj_getCoords, open_item, pad,
  payment_method_change, poll, popup_calendar_large, popup_dialog, popup_email_to_friend,
  popup_help, popup_hide_on_loaded, popup_layer, popup_layer_submit, popup_map, popup_map_general,
  popup_map_general_maximize, popup_wait, popWin, popWin_post, print_form, print_form_data, print_friendly,
  radio_group_get, radio_group_set, radio_groups_group_set, rating_blocks_init,
  rating_submit, register_required, removeEvent,
  report_filter_ondrag, report_filter_setup_onmouseout, report_filter_set,
  report_filter_setup, report_filter_setup_dragable, report_filter_setup_onclick,
  report_filter_setup_ondrag, report_filter_setup_onmouseover, restoreTabIndexes, round,
  RTrim, sajax, SDMenu, search_go, search_offset, search_results_go, search_setup_date_range,
  select_item, select_YYYY_MM, selector_csv_add, selector_csv_delete, selector_csv_edit,
  selector_csv_show, setBackground, setBlock, setDisplay, setFocus, setScreenSize,
  setTextSize, show_popup_please_wait, show_section, showPopWin,
  setupSwatches, signup_required, status_message_hide, status_message_show, textCounter,
  toggle_attachment_delete_flag, toggleTextSize, topbar_search_go, Trim, twitter_profile, two_dp,
  validate_payment_details, version, view_credit_memo, view_event_registrants,
  view_order_details, widget_toggle;

// ************************************
// * Browser detection:               *
// ************************************
var isNS4 =	    (navigator.appName.indexOf("Netscape")>=0 && !document.getElementById) ? true : false;
var isNS6 =	    (document.getElementById && navigator.appName.indexOf("Netscape")>=0 ) ? true : false;
var isW3C =	    (document.getElementById && 1) ? true : false;
var isIE =	    (document.uniqueID) ? true : false;
var isIEMac =   (navigator.userAgent.toLowerCase().indexOf('mac') != -1 && navigator.userAgent.toLowerCase().indexOf('msie 5') != -1) ? true : false;
var isIE4 =	    (isIE && !document.getElementById) ? true : false;
var isIE5 =	    (isIE && window.clipboardData && !window.createPopup) ? true : false;
var isIE55 =	(isIE && window.createPopup && !document.compatMode) ? true : false;
var isIE6 =	    (isIE && document.compatMode && !window.XMLHttpRequest) ? true : false;
var isIE7 =	    (isIE && window.XMLHttpRequest) ? true : false;
var isIE8 =	    (isIE && window.toStaticHTML) ? true : false;
var isIE9 =	    (isIE && window.performance) ? true : false;
var isIE_lt6 =	(isIE && !document.compatMode) ? true : false;
var isIE_lt7 =	(isIE && !window.XMLHttpRequest) ? true : false;
var isIE_lt8 =	(isIE && !window.toStaticHTML) ? true : false;
var isIE_lt9 =  (isIE && !window.performance)? true : false;
var isSafari =  (typeof navigator.vendor!=='undefined' && navigator.vendor.indexOf('Apple')!==-1 ? true : false);
var isOpera =   (typeof window.opera!=='undefined' ? true : false);

var ajax_controls = [];
var applets_list =  [];
var _CM = {};
var popup_msg =	'';
var quot = String.fromCharCode(34);

var ToolTips = {
  CURRENT: null,
  TIMEOUT: null,
  LINK: null,
  attachBehavior: null,
  over: null,
  out: null,
  show: null
};

// These are for browsers with JS < 1.2 such as IE5
if (!Array.prototype.pop) {
  Array.prototype.pop = function() {
    var temp = this[this.length-1];
    this.length--;
    return temp;
  };
}

if (!Array.prototype.push) {
  Array.prototype.push = function(item) {
    this[this.length] = item;
    return this.length;
  };
}

/*
  Ref: http://webreflection.blogspot.com/2008/12/outerhtml-for-almost-every-browser-if.html
  Andrea Giammarchi, Berlin, Germany
*/
try{
  HTMLElement.prototype.__defineGetter__.length;
  (function(body, removeChild){
    HTMLElement.prototype.__defineGetter__(
        "outerHTML",
        function(){
            var self = body.appendChild(this.cloneNode(true)),
                outerHTML = body.innerHTML;
            body.removeChild(self);
            return outerHTML;
        }
    );
    HTMLElement.prototype.__defineSetter__(
        "outerHTML",
        function(String){
            if(!String)
                removeChild(this);
            else if(this.parentNode){
                body.innerHTML = String;
                while(body.firstChild)
                    this.parentNode.insertBefore(body.firstChild, this);
                removeChild(this);
                body.innerHTML = "";
            };
        }
    );
  })(
    document.createElement("body"),
    function(HTMLElement){if(HTMLElement.parentNode)HTMLElement.parentNode.removeChild(HTMLElement);}
  );
}
catch(e){
};




if(typeof encodeURIComponent !== "function") {
  window.encodeURIComponent = function(str){
    var decVals, encVals, i, re;
    decVals = ["%",'"'," ","<",">","\\[","\\]","\\\\","\\^","~","\\{","\\}","\\|","\\:",";","#","\\$","&",",","/","=","\\?","@"];
    encVals = ["%25","%22","%20","%3C","%3E","%5B","%5D","%5C","%5E","~","%7B","%7D","%7C","%3A","%3B","%23","%24","%26","%2C","%2F","%3D","%3F","%40"];
    for(i=0;i<decVals.length;i++){
      document.title = decVals[i];
      re = new RegExp(decVals[i],"gi");
      str = str.replace(re,encVals[i]);
    }
    return str;
  };
}

var sajax = {
  request: function(func_name, args) {
    var rsargs = [];
    for (i=0; i<args.length-1; i++){
      rsargs.push(args[i]);
    }
    $.ajax({
      type: 'POST',
      url:  base_url+'ajax/',
      dataType: 'text',
      data: {
        rs: func_name,
        rsrnd: new Date().getTime(),
        'rsargs[]': rsargs
      }
    }).done(
      function(data){
        try {
          var callback = args[args.length-1];
          callback(eval(data), false);
        }
        catch (e) {
//          alert(e + ": Could not eval data from " + url + "\n"+ data);
        }
      }
    );
  },
  lookup_handler: function() {
    sajax.request('serve_lookup_report',arguments);
  },
  lookup_helper: function(num,show){
    $('#ajax_result_'+num).parent().css({'overflow':(show ? 'auto' : 'hidden')});
    sajax.lookup_wait(num,0);
    setDisplay('ajax_result_show_'+num,!show);
    setDisplay('ajax_result_hide_'+num,show);
    setDisplay('ajax_result_'+num,show);
  },
  lookup_wait: function(num,show){
    setDisplay('ajax_wait_show_'+num,show);
    setDisplay('ajax_wait_hide_'+num,!show);
  },
  lookup_toggle: function(num){
    var show = ($('#ajax_result_'+num).css('display')==='none' ? 1 : 0);
    sajax.lookup_helper(num,show);
  }
}


ecc_map = {};
ecc_map.point = function(map,lat,lon,title,html,showinfo,draggable,icon,shadow,shape){
  var marker;
  if (typeof google.maps.Marker==='undefined'){
    return false;
  }
  var args = {
    draggable:  (typeof draggable!=='undefined' && draggable ? true : false),
    map:        map,
    position:   new google.maps.LatLng(lat,lon),
    title:      title
  }
  if (typeof icon!=='undefined'   && icon)   { args.icon =   icon; }
  if (typeof shadow!=='undefined' && shadow) { args.shadow = shadow; }
  if (typeof shape!=='undefined'  && shape)  { args.shape =  shape; }
  marker = new google.maps.Marker(args);
  marker.html = html
  ecc_map.point.c(marker);
  if (typeof draggable!=='undefined' && draggable){
    ecc_map.point.d(marker,lat,lon);
  }
  if (typeof showinfo!=='undefined' && showinfo){
    ecc_map.point.i(marker);
  }
  return marker;
}

ecc_map.point.c = function(marker){
  if (typeof google.maps.event.addListener==='undefined'){
    return;
  }
  google.maps.event.addListener(
    marker,
    'click',
    function() {
      ecc_map.point.i(this)
    }
  );
}

ecc_map.point.d = function(marker,lat,lon){
  ecc_map.point.m(marker,lat,lon);
  google.maps.event.addListener(
    marker,
    "dragend",
    function() {
      ecc_map.point.i(marker)
//      infoWindow.setContent(marker.html);
      geid_set('targetValue',marker.getPosition().toUrlValue());
    }
  );
}

ecc_map.point.i = function(marker){
  infoWindow.setContent(marker.html);
  infoWindow.open(marker.map,marker);
  return false;
}

ecc_map.point.m = function(marker,lat,lon){
  marker.old_lat = lat;
  marker.old_lon = lon;
}

ecc_map.point.r = function(marker){
  marker.setPosition(new google.maps.LatLng(marker.old_lat,marker.old_lon));
  infoWindow.close();
  return false;
}

ecc_map.point.s = function(ID,marker,url,submode,actions_div) {
  if (geid_val('targetValue')=='') {
    alert('No changes to save.\nClick on the marker to move it first.');
  }
  else {
    if (confirm('Save changes for this marker?')) {
      var coords = geid_val('targetValue');
      var coords_arr = coords.split(',');
      ecc_map.point.m(marker,coords_arr[0],coords_arr[1]);
      xFn = function(){}
      ajax_post(url+'?submode='+submode+'&targetID='+ID+'&targetValue='+coords,actions_div,'',xFn);
    }
    else {
      alert('Changes have not been saved');
    }
  }
  return false;
}

function geid(id) {
  if(typeof(id)==='string' && id!=='') {
    if (document.getElementById(id)) {
      return document.getElementById(id);
    }
    if (document.getElementById('form') && document.getElementById('form').elements[id]){
      return document.getElementById('form').elements[id];
    }
    element_arr = document.getElementsByName(id);
    if(element_arr) {
      return element_arr[0];
    }
    return null;
  }
  return id;
}

function geid_set(id,value) {
  // FF fails on getElementByID where ID is one of several
  var n, obj;
  obj = geid(id);
  if (obj && typeof obj.type !=='undefined'){
    switch (obj.type) {
      case 'checkbox':
        obj.checked = value;
        return true;
      case 'file':
      case 'hidden':
      case 'password':
      case 'text':
      case 'textarea':
        obj.value = value;
        return true;
      case 'radio':
        $('input:radio[name=\"'+id+'\"]').val([value])
        return true;
      case 'select-one':
        for (n=0; n<obj.length; n++){
          if (obj.options[n].value===value) {
            obj.selectedIndex = n;
            return true;
          }
        }
        return false;
    }
  }
//  alert("Error:\ngeid_set('"+id+"') found unhandled object type of "+obj.type);
  return false;
}

function geid_val(id) {
  if (id==='') {
    return false;
  }
  // FF fails on getElementByID where ID is one of several
  var obj, element_arr;
  obj = geid(id);
  if (obj && typeof obj.type !=='undefined'){
    switch (obj.type) {
      case 'checkbox':
        return (obj.checked ? obj.value : '');
      case 'file':
      case 'hidden':
      case 'password':
      case 'text':
      case 'textarea':
        return obj.value;
      case 'radio':
        return radio_group_get(id);
      case 'select-one':
        if (obj.selectedIndex===-1){
          return "";
        }
        return obj.options[obj.selectedIndex].value;
    }
  }
  if (document.getElementsByName) {
    element_arr = document.getElementsByName(id);
    if (typeof element_arr[0]==='undefined') {
      return false;
    }
    if (element_arr[0].type==='radio'){
      return radio_group_get(id);
    }
  }
//  alert("Error:\ngeid_val('"+id+"') found unhandled object type "+element_arr[0].type);
  return obj.value;
}

function get_tallest_child(id){
  return Math.max.apply(
    null, $('#'+id).children().map(
      function(){
        return $(this).height();
      }
    ).get()
  );
}

function set_height_from_tallest_child(id){
  $('#'+id).height(get_tallest_child(id));
}


function hhmm_format(hhmm){
  var ampm, hh, mm, hhmm_arr;
  if (hhmm===''){
    return;
  }
  if (hhmm.indexOf(':')===-1 && hhmm.length<=2){
    hhmm+=':00';
  }
  hhmm_arr = hhmm.split(':');
  hh = parseFloat(hhmm_arr[0]);
  mm = lead_zero(hhmm_arr[1],2);
  if (defaultTimeFormat===0 || defaultTimeFormat===2){
    return lead_zero(hh)+':'+mm;
  }
  switch(hhmm){
    case '0:00':
    case '00:00':
      return 'Midnight';
    break;
    case '12:00':
      return 'Noon';
    break;
  }
  if (hh<12){
    ampm = 'am';
  }
  else {
    if (hh>12){
      hh = hh-12;
    }
    ampm = 'pm';
  }
  return hh+":"+mm+ampm;
}

function nav_setup(nav, isAdmin, url, responsive){
    var li_arr, a_arr, mouseout_state, i, j, ul_arr, axis;
    li_arr = $('#nav_root_'+nav+' ul li');
        for(i=0; i<li_arr.length; i++){
        a_arr = li_arr[i].childNodes;
        for(j=0; j<a_arr.length; j++){
            if (typeof a_arr[j].tagName!=='undefined' && a_arr[j].tagName.toLowerCase()==='a'){
                mouseout_state = a_arr[j].className==='nav_active' ? 'a' : 'n';
                nav_setup_attach(li_arr[i], mouseout_state, responsive);
            }
        }
    }
    if (isAdmin && !responsive){
        ul_arr = $('#nav_root_'+nav+' ul');
        for(i=0; i<ul_arr.length; i++){
            id =    ul_arr[i].id.toString();
            axis =   (ul_arr[i].className.substr(0,8)=='hnavmenu' || ul_arr[i].className.substr(0,8)=='rhnavmenu' ? 'x' : 'y');
            nav_setup_sortable(id, axis, url);
        }
    }
}

function nav_setup_attach(obj, mouseout_state, responsive){
    if (responsive) {
        addEvent(obj, 'mouseout',  function(e){ applets_show(); _CM.type='';});
        addEvent(obj, 'mouseover', function(e){ applets_hide();});
        return;
    }
    addEvent(obj, 'mousedown', function(e){ return nav_setup_state(obj,'d');});
    addEvent(obj, 'mouseover', function(e){ applets_hide(); nav_setup_state(obj,'o')});
    addEvent(obj, 'mouseout',  function(e){ applets_show(); _CM.type=''; return nav_setup_state(obj,mouseout_state)});
}

function nav_setup_sortable(id,axis,url){
  $('#'+id).sortable({
    axis: axis,
    opacity: 0.8,
    update: function(event, ui){
      var items = $(this).sortable('toArray');
      $.each(items,function(index,item){items[index] = item.split('_').pop();});
      seq = items.toString();
      $.post(
        base_url,{
          command:      'navsuite_seq',
          targetID:     id.split('_').pop(),
          targetValue:  seq
        },
        function(json){
          for (i=0; i<json.length; i++){
            $('#btn_'+json[i][0]+' img')[0].style.backgroundImage='url('+base_url+'img/button/'+json[i][0]+'/'+json[i][1]+')';
          }
        },
        'json'
      );
    }
  });
}

function nav_setup_fix_mouseovers(navRoot) {
  var i, j, k, node, subnode, subsubnode;
  for (i=0; i<navRoot.childNodes.length; i++) {
    node = navRoot.childNodes[i];
    if (node.nodeName==='LI') {
      node.onmouseover=function() {
        this.className+=' over';
        this.style.zIndex=2;
      };
      node.onmouseout=function() {
        this.className=this.className.replace(' over', '');
        this.style.zIndex=1;
      };
      for (j=0; j<node.childNodes.length; j++) {
        subnode = node.childNodes[j];
        if (subnode.nodeName==='UL') {
          subnode.onmouseover=function() {
            this.parentNode.style.zIndex=2;
            this.style.zIndex=2;
          };
          subnode.onmouseout=function() {
            this.parentNode.style.zIndex=1;
            this.style.zIndex=1;
          };
          for (k=0; k<subnode.childNodes.length; k++) {
            subsubnode = subnode.childNodes[k];
            if (subsubnode.nodeName==='LI') {
              subsubnode.onmouseover=function() {
                this.className+=' over';
                this.parentNode.style.zIndex=2;
                this.style.zIndex=2;
              };
              subsubnode.onmouseout=function() {
                this.className=this.className.replace(' over', '');
                this.parentNode.style.zIndex=1;
                this.style.zIndex=1;
              };
            }
          }
        }
      }
    }
  }
}

function nav_setup_state(listitem,state) {
  var h, new_pos, img, pos_arr;
  img =	listitem.childNodes[0].childNodes[0];
  if (img.tagName.toLowerCase()!=='img'){
    return true;
  }
  h =	img.height;
  pos_arr =	img.style.backgroundPosition.split(' ');
  new_pos = "";
  switch (state) {
    case 'a':
      new_pos = 0;
    break;
    case 'd':
      new_pos = -1*h;
    break;
    case 'n':
      new_pos = -2*h;
    break;
    case 'o':
      new_pos = -3*h;
    break;
  }
  img.style.backgroundPosition = pos_arr[0]+' '+new_pos+'px';
  return true;
}


function keytest(e,type,obj){
  var keynum, keychar, rexp;
  rexp = false;
  if (window.event) {
    keynum = e.keyCode;
  }
  else {
    keynum = e.which;
  }
  keychar = String.fromCharCode(keynum);
  switch (type) {
    case 'cart':
      rexp = /[\d]/;
    break;
    case 'currency':
      rexp = /[\d\.\-]/;
    break;
    case 'currency_s':
      rexp = /[\d\.]/;
    break;
    case 'date':
      rexp = /[\d\-]/;
    break;
    case 'datetime':
      rexp = /[\d\-\: ]/;
    break;
    case 'hh:mm':
      rexp = /[\d\:]/;
    break;
    case 'int':
    case 'seq':
      rexp = /[\d\-]/;
    break;
    case 'int_s':
      rexp = /[\d]/;
    break;
    case 'listdata_value':
      if(geid(obj.id+'_path_safe') && !geid_val(obj.id+'_path_safe')){
        return true;
      }
      rexp = /[\d\-\_a-zA-Z]/;
    break;
    case 'posting_name':
      rexp = /[\d\-\_a-zA-Z]/;
    break;
    case 'qty':
      rexp = /[\d]/;
    break;
    case 'percent':
      rexp = /[\d\.\-]/;
    break;
    case 'swatch':
      if (e.keyCode>96 && e.keyCode<122){
        e.keyCode-=32;
      }
      rexp = /[\dabcdefABCDEF]/;
    break;
    default:
//      alert(type+' is not handled');
    break;
  }
  var result = rexp!==false && rexp.test(keychar);
  if (!result && e.preventDefault) {
    if (!isIE || isIE_lt9){
      switch (e.keyCode) {
        case 8: case 9: case 27: case 35: case 36: case 37: case 38: case 39: case 40: case 46:
        break;
        default:
          e.preventDefault();
        break;
      }
    }
    else {
      e.preventDefault();
    }
  }
  return result;
}

function keytest_enter_execute(e,fn) {
  var keynum = (window.event ? e.keyCode : e.which);
  if(keynum === 13) {
    fn();
    return false;
  }
  return true;
}

function keytest_enter_transfer(e,btn) {
  var fn = function(){
    geid(btn).focus();
    geid(btn).click();
  };
  return keytest_enter_execute(e,fn);
}

function match_picture(picture,val) {
  var i, rexp;
  for (i=0; i<picture.length; i++){
    if (val.length<=i) {
      break;
    }
    switch(picture.substr(i,1)) {
      case "0":
        rexp = /[\d]/;
        if (!rexp.test(val.substr(i,1))){
          return val.substr(0,i);
        }
      break;
      case "-":
        rexp = /[\-]/;
        if (!rexp.test(val.substr(i,1))){
          return val.substr(0,i)+"-";
        }
      break;
      case " ":
        rexp = /[ ]/;
        if (!rexp.test(val.substr(i,1))){
          return val.substr(0,i)+" ";
        }
      break;
      case ":":
        rexp = /[\:]/;
        if (!rexp.test(val.substr(i,1))){
          return val.substr(0,i)+":";
        }
      break;
    }
  }
  return val;
}

function attach_field_behaviour(id,type,args) {
  var _hh, _mm, _ss, dd, fn, fn2, leap, mm, obj, src, val, yyyy;
  obj = geid(id);
  if (!obj) {
    return;
  }
  switch (id){
    case 'effective_date_start':
      fn = function(e){
        geid('effective_date_end').value=geid('effective_date_start').value;
      }
      addEvent(obj, "blur", fn);
    break;
    case 'effective_date_end':
      fn = function(e){
        if(geid('effective_date_end').value==''){
          geid('effective_date_end').value=geid('effective_date_start').value;
        }
        if(geid('effective_date_end').value<geid('effective_date_start').value){
          geid('effective_date_end').value=geid('effective_date_start').value;
          var h = "<div id='popup_form' style='padding:4px;'><b>Sorry!</b><br />End Date cannot be before Start Date.<br /></div>";
          popup_dialog('Event Dates',h,240,100,'OK','','');
        }
      }
      addEvent(obj, "blur", fn);
    break;
    case 'effective_time_start':
      fn = function(e){
//        geid('effective_time_end').value=geid('effective_time_start').value;
      }
      addEvent(obj, "blur", fn);
    break;
    case 'effective_time_end':
      fn = function(e){
        if(geid('effective_date_end').value==geid('effective_date_start').value && geid('effective_time_end').value && geid('effective_time_start').value>geid('effective_time_end').value){
          var h = "<div id='popup_form' style='padding:4px;'><b>Sorry!</b><br />End Time cannot be before Start Time for a single-day event.<br /></div>";
          popup_dialog('Event Times',h,240,100,'OK','','');
          geid('effective_time_end').value=geid('effective_time_start').value;
        }
      }
      addEvent(obj, "blur", fn);
    break;
  }
  switch (type){
    case 'cart':
      // Up button:
      fn = function(e){geid_set(id,parseInt(geid_val(id),10)+1);geid(id).onchange();};
      addEvent(geid(id+'_up'), "click", fn);
      fn = function(e){geid(id+'_up').style.backgroundPosition='-10px 0px';};
      addEvent(geid(id+'_up'), "mouseover", fn);
      fn = function(e){geid(id+'_up').style.backgroundPosition='0px 0px';};
      addEvent(geid(id+'_up'), "mouseout", fn);
      // Down button:
      fn = function(e){if (parseInt(geid_val(id),10)>0){geid_set(id,parseInt(geid_val(id),10)-1);geid(id).onchange();}};
      addEvent(geid(id+'_down'), "click", fn);
      fn = function(e){if (parseInt(geid(id).value,10)>0){geid(id+'_down').style.backgroundPosition='-176px 0px';}};
      addEvent(geid(id+'_down'), "mouseover", fn);
      fn = function(e){if (parseInt(geid(id).value,10)>0){geid(id+'_down').style.backgroundPosition='-166px 0px';}};
      addEvent(geid(id+'_down'), "mouseout", fn);
      if(parseInt(obj.value,10)===0){
        geid(id+'_down').style.backgroundPosition='-186px 0px';
      }
      else {
        geid(id+'_down').style.backgroundPosition='-166px 0px';
      }
    break;
    case 'currency':
    case 'currency_s':
      fn = function(e){
        val = geid(id).value;
        if (typeof args!=='undefined' && typeof args.min!=='undefined') {
          val = (val < args.min ? args.min : val);
        }
        if (typeof args!=='undefined' && typeof args.max!=='undefined') {
          val = (val > args.max ? args.max : val);
        }
        geid(id).value=two_dp(val);
        if (typeof geid(id).onchange==='function'){
          geid(id).onchange();
        }
      };
      addEvent(obj, "blur", fn);
    break;
    case 'date':
    case 'datetime':
    case 'effective_date_start':
    case 'effective_date_end':
      fn = function(e){
        val = match_picture('0000-00-00 00:00',geid_val(id).toString());
        if (val.length>=4) {
          yyyy = parseFloat(val.substr(0,4));
          leap = ((yyyy%4===0) && (yyyy%100!==0)) || (yyyy%400===0);
          if (yyyy!==0 && yyyy<1900) {
            geid_set(id,'1900');
            return;
          }
          if (yyyy>2100) {
            geid_set(id,'2100');
            return;
          }
        }
        if (val.length>=7) {
          mm = parseFloat(val.substr(5,2));
          if (mm<1) {
            geid_set(id,val.substr(0,5)+'01');
            return;
          }
          if (mm>12) {
            geid_set(id,val.substr(0,5)+'12');
            return;
          }
        }
        if (val.length>=10) {
          dd = parseFloat(val.substr(8,2));
          if (dd<1) {
            geid_set(id,val.substr(0,8)+'01');
            return;
          }
          if (!leap && mm===2 && dd>28) {
            geid_set(id,val.substr(0,8)+'28');
            return;
          }
          if (leap && mm===2 && dd>29) {
            geid_set(id,val.substr(0,8)+'29');
            return;
          }
          if ((mm===4 ||  mm===6 || mm===9 || mm===11) && dd>30) {
            geid_set(id,val.substr(0,8)+'30');
            return;
          }
          if (dd>31) {
            geid_set(id,val.substr(0,8)+'31');
            return;
          }
        }
        if (val.length>=13) {
          _hh = parseFloat(val.substr(11,2));
          if (_hh<1) {
            geid_set(id,val.substr(0,11)+'00');
            return;
          }
          if (_hh>23) {
            geid_set(id,val.substr(0,11)+'23');
            return;
          }
        }
        if (val.length>=16) {
          _mm = parseFloat(val.substr(14,2));
          if (_mm<1) {
            geid_set(id,val.substr(0,14)+'00');
            return;
          }
          if (_mm>59) {
            geid_set(id,val.substr(0,14)+'59');
            return;
          }
        }
        if (val.length>=19) {
          _ss = parseFloat(val.substr(17,2));
          if (_ss<1) {
            geid_set(id,val.substr(0,17)+'00');
            return;
          }
          if (_ss>59) {
            geid_set(id,val.substr(0,17)+'59');
            return;
          }
        }
        geid_set(id,val);
      };
      addEvent(obj, "keyup", fn);
      new tcal({
        'controlname': id
      });
    break;
    case 'hh:mm':
      fn = function(e){
        val = match_picture('00:00',geid_val(id).toString());
        var hh_1, hh_10, mm_10;
        if (val.length>=1) {
          hh_10 = parseInt(val.substr(0,1),10);
          if (hh_10>2) {
            geid_set(id,'2'+val.substr(1));
            return;
          }
        }
        if (val.length>=2) {
          hh_1 = parseInt(val.substr(1,1),10);
          if (hh_10===2 && hh_1>3) {
            geid_set(id,val.substr(0,1)+'3'+val.substr(2));
            return;
          }
        }
        if (val.length>=4) {
          mm_10 = parseInt(val.substr(3,1),10);
          if (mm_10>5) {
            geid_set(id,val.substr(0,3)+'5');
            return;
          }
        }
        geid_set(id,val);
      };
      addEvent(obj, "keyup", fn);
    break;
    case 'listdata_value':
      src = 'textEnglish';
      fn =
        function(e){
          if (geid(id) && geid_val(id)==='' && geid(src) && geid_val(src).substring(0,1)!=='('){
            geid_set(id,geid_val(src));
          }
          if (geid(id+'_path_safe') && geid_val(id+'_path_safe')){
            var name =
              geid_val(id).
                replace(/[\(\)]/g,'').replace(/ {2}/g,' ').
                replace(/ - /g,'-').replace(/ /g,'-').
                replace(/\%/g,'pc').replace(/[^0-9\_\-a-zA-Z\(\)\%]/g,'');
          }
          else {
            var name = geid_val(id);
          }
          geid_set(id,name);
        };
      addEvent(window,'load',fn);
      addEvent(geid(id), 'change', fn);
      addEvent(geid(src), 'change', fn);
    break;
    case 'posting_name':
      src = 'title';
      if (!geid(src)){
        src = 'new_title';
      }
      fn =
        function(e){
          geid_set(src,geid_val(src).replace(/\"/g,"'"));
          if (geid(id) && geid_val(id)==='' && geid(src)){
            geid_set(id,geid_val(src));
          }
          if (id!=='itemCode'){
            geid_set(id,geid_val(id).toLowerCase());
          }
          var name =
            geid_val(id).
              replace(/[\(\)]/g,'').replace(/ {2}/g,' ').
              replace(/ - /g,'-').replace(/ /g,'-').
              replace(/\%/g,'pc').replace(/[^0-9\-\_a-zA-Z\(\)\%]/g,'');
          geid_set(id,name);
        };
      addEvent(window,'load',fn);
      addEvent(geid(id), 'change', fn);
      addEvent(geid(src), 'change', fn);
    break;
    case 'qty':
      // Up button:
      fn = function(e){geid_set(id,parseInt(geid_val(id),10)+1);geid(id).onchange();};
      addEvent(geid(id+'_up'), "click", fn);
      fn = function(e){geid(id+'_up').style.backgroundPosition='-1434px 0px';};
      addEvent(geid(id+'_up'), "mouseover", fn);
      fn = function(e){geid(id+'_up').style.backgroundPosition='-1423px 0px';};
      addEvent(geid(id+'_up'), "mouseout", fn);
      // Down button:
      fn = function(e){if (parseInt(geid_val(id),10)>0){geid_set(id,parseInt(geid_val(id),10)-1);geid(id).onchange();}};
      addEvent(geid(id+'_down'), "click", fn);
      fn = function(e){if (parseInt(geid(id).value,10)>0){geid(id+'_down').style.backgroundPosition='-1434px 8px';}};
      addEvent(geid(id+'_down'), "mouseover", fn);
      fn = function(e){if (parseInt(geid(id).value,10)>0){geid(id+'_down').style.backgroundPosition='-1423px 8px';}};
      addEvent(geid(id+'_down'), "mouseout", fn);
      if(parseInt(obj.value,10)===0){
        geid(id+'_down').style.backgroundPosition='-1445px 8px';
      }
      else {
        geid(id+'_down').style.backgroundPosition='-1423px 8px';
      }
    break;
    case 'readonly':
      obj.style.backgroundColor='#f0f0f0';
      obj.style.color='#404040';
      fn = function(e){this.blur();};
      addEvent(obj, "focus", fn);
    break;
    case 'seq':
      if (geid_val('ID')) {
        geid(id).style.backgroundColor="";
        geid(id+'_up').style.backgroundPosition='-1423px 0px';
        switch (parseInt(obj.value,10)){
          case 0:
          case 1:
          break;
          default:
            geid(id+'_down').style.backgroundPosition='-1423px 8px';
          break;
        }
      }
      // Up button:
      fn = function(e){
        if (geid_val('ID')){
          geid(id).style.backgroundColor='#e8e8e8';
          geid(id+'_up').onclick=null;
          geid(id+'_up').onmouseover=null;
          geid(id+'_up').onmouseout=null;
          geid(id+'_down').onclick=null;
          geid(id+'_down').onmouseover=null;
          geid(id+'_down').onmouseout=null;
          geid('targetField').value=id;
          geid('targetValue').value=parseInt(geid(id).value,10)+1;
          geid('submode').value='seq_up';
          geid(id+'_up').style.backgroundPosition='-1445px 0px';
          geid(id+'_down').style.backgroundPosition='-1445px 8px';
          geid('form').submit();
        }
      };
      geid(id+'_up').onclick=fn;
      fn = function(e){
        if (geid_val('ID')) {
          geid(id+'_up').style.backgroundPosition='-1434px 0px';
        }
      };
      geid(id+'_up').onmouseover=fn;
      fn = function(e){
        if (geid_val('ID')) {
          geid(id+'_up').style.backgroundPosition='-1423px 0px';
        }
      };
      geid(id+'_up').onmouseout=fn;
      // Down button:
      fn = function(e){
        if (geid_val('ID') && parseInt(geid(id).value,10)>0){
          geid(id).style.backgroundColor='#e8e8e8';
          geid(id+'_up').onclick=null;
          geid(id+'_up').onmouseover=null;
          geid(id+'_up').onmouseout=null;
          geid(id+'_down').onclick=null;
          geid(id+'_down').onmouseover=null;
          geid(id+'_down').onmouseout=null;
          geid('targetField').value=id;
          geid('targetValue').value=parseInt(geid(id).value,10)-1;
          geid('submode').value='seq_down';
          geid(id+'_up').style.backgroundPosition='-1445px 0px';
          geid(id+'_down').style.backgroundPosition='-1445px 8px';
          geid('form').submit();
        }
      };
      geid(id+'_down').onclick=fn;
      fn = function(e){
        if (geid_val('ID') && parseInt(geid(id).value,10)>0){
          geid(id+'_down').style.backgroundPosition='-1434px 8px';
        }
      };
      geid(id+'_down').onmouseover=fn;
      fn = function(e){
        if (geid_val('ID') && parseInt(geid(id).value,10)>0){
          geid(id+'_down').style.backgroundPosition='-1423px 8px';
        }
      };
      geid(id+'_down').onmouseout=fn;
    break;
    case 'swatch':
      obj.value=obj.value.toLowerCase();
    break;
  }
  fn = function(e){return keytest(e,type,obj);};
  addEvent(obj, "keypress", fn);
}

function add_bookmark(url, title) {
  if (window.sidebar) { // firefox
    window.sidebar.addPanel(title, url, "");
    return;
  }
  if(window.opera && window.print) { // opera
    var elem = document.createElement('a');
	elem.setAttribute('href',url);
	elem.setAttribute('title',title);
	elem.setAttribute('rel','sidebar');
	elem.click();
    return;
  }
  if(document.all) { // ie
    window.external.AddFavorite(url, title);
    return;
  }
  alert("Sorry! Your browser doesn't allow us to add a bookmark this way.");
}


function afb(id,type,args) {
  return attach_field_behaviour(id,type,args);
}

function ap_registerPlayers() {
  var i, objectID, objectTags;
  objectTags = document.getElementsByTagName("object");
  for(i=0; i<objectTags.length; i++) {
    objectID = objectTags[i].id;
    if(objectID.indexOf("audioplayer") == 0) {
      ap_instances[i] = objectID.substring(11, objectID.length);
    }
  }
}

function ap_stopAll(playerID) {
  var i;
  for (i=0; i<ap_instances.length; i++) {
    try {
      if(ap_instances[i] != playerID){
        geid("audioplayer" + ap_instances[i].toString()).SetVariable("closePlayer",1);
      }
      else{
        geid("audioplayer" + ap_instances[i].toString()).SetVariable("closePlayer", 0);
      }
    }
    catch( errorObject ) {
      // stop any errors
    }
  }
}
function applet_register(id){
  applets_list.push(id);
}

function applets_hide(){
  var container, height, i, pos, shimmer, width;
  for (var i=0; i<applets_list.length; i++){
    container = $('container_'+applets_list[i])[0];
    if (container){
      pos =     container.offset();
      height =  container.height();
      width =   container.width();
      shimmer = document.createElement('iframe');
      shimmer.id='shimmer_'+applets_list[i];
      shimmer.style.position='absolute';
      shimmer.style.width=width+'px';
      shimmer.style.height=height+'px';
      shimmer.style.left=pos[0]+'px';
      shimmer.style.top=pos[1]+'px';
      shimmer.style.zIndex=1;
      shimmer.setAttribute('frameborder','0');
      shimmer.setAttribute('src',base_url+'img/spacer');
      document.body.appendChild(shimmer);
    }
  }
}

function applets_show(){
  var i, shimmer;
  for (var i=0; i<applets_list.length; i++){
    shimmer = geid('shimmer_'+applets_list[i]);
    if (shimmer){
      document.body.removeChild(shimmer);
    }
  }
}

function autocomplete_off() {
  var input, inputs, i;
  if (!document.getElementsByTagName){
    return;
  }
  inputs = document.getElementsByTagName("input");
  for (i=0; i<inputs.length; i++) {
    input = inputs[i];
    try{
      if (input.className.indexOf('autocomplete_off')>-1){
        input.setAttribute('autocomplete','off');
      }
    }
    catch (e) {
    }
  }
}

function bugtracker_form(){
  var h = "<div id='popup_form' style='padding:4px;'>Loading...</div>";
  popup_dialog('Report a Bug',h,524,400,'','');
  var post_vars = 'ajax=1';
  popup_layer_submit(base_url+'_bug',post_vars);
  return false;
}

function bugtracker_form_onsubmit(){
  geid('bugtracker_form_cancel').disabled=true;
  geid('bugtracker_form_submit').disabled=true;
  var post_vars =
    'ajax=1'+
    '&subject='+geid_val('bugtracker_form_subject')+
    '&submode=bugtracker_submit';
  popup_layer_submit(base_url+'_bug',post_vars);
}

function cal_events(YYYYMMDD){
  this.record =     [];
  this.height =     500;
  this.target =     'popup_form_content';
  this.title =      'Events for '+YYYYMMDD;
  this.width =      750;
  this.YYYYMMDD =   YYYYMMDD;
  this.YYYY =       YYYYMMDD.substr(0,4);
  this.MM =         YYYYMMDD.substr(5,2);
  this.DD =         YYYYMMDD.substr(8,2);
}

cal_events.prototype.draw = function(can_edit){
  this.draw_frame();
  var self = this;
  $.ajax({
    data: {YYYYMMDD: self.YYYYMMDD},
    dataType: 'json',
    type: 'POST',
    url:  base_url+'ajax/events'
  })
  .done(function(transport){
    self.handler(transport,can_edit);
  });
};

cal_events.prototype.handler = function(transport,can_edit){
  this.json = transport;
  this.display(can_edit);
};

cal_events.prototype.draw_frame = function(){
  var html =
    "<div id='popup_form'>\n" +
    "<div id='popup_form_content' style='padding:4px;height:"+(this.height-50)+"px;>\n" +
    "<img class='fl' src='"+base_url+"img/sysimg/progress_indicator.gif' width='16' height='16' alt='Please wait...' style='margin:0 5px 0 0'>\n" +
    " Loading...\n" +
    "</div>\n" +
    "</div>";
  popup_dialog(this.title,html,this.width,this.height,'','Close');
};

cal_events.prototype.display_content = function(){
  return (
    this.record.content ?
       "<div style='float:left;width:"+
       (this.width-(this.record.icon ? 110 : 30))+
       "px;margin:5px 0 0 0'>\n"+
       this.record.content+
       "</div>\n"
     : ""
  );
};

cal_events.prototype.display_shared = function(){
  return (
    this.record.shared===1 ?
      "<a href=\"" + this.record.systemURL + "\" " +
      "title=\"Shared by " + this.record.systemTitle + " - click to visit\" " +
      "target=\"_blank\">" +
      "<img src=\"" + base_url + "img/spacer\" class=\"icons\" style=\"padding:0;margin:0 2px 0 0;height:13px;width:15px;background-position:-1173px 0px;\" " +
      "alt=\"External content from " + this.record.systemTitle + "\" />\n" +
      "<b>" + this.record.systemTitle + "</b>" +
      "</a><br class='clr_b' />"
    : ""
   );
};

cal_events.prototype.display_icon = function(){
  return (
    this.record.icon ?
      "<img src=\""+this.record.icon+"\" style=\"float:left;margin:5px 5px 5px 0;\" alt=\""+this.record.title+"\" />"
    : ""
   );
};

cal_events.prototype.display_times = function(){
  if (this.record.effective_time_start==='' && this.record.effective_time_end===''){
    return "<b>(All day)</b><br />";
  }
  if (this.record.effective_time_start === this.record.effective_time_end){
    return "<b>at "+hhmm_format(this.record.effective_time_start)+"</b>";
  }
  return "<b>"+
    (this.record.effective_time_end==="" ? "From "+hhmm_format(this.record.effective_time_start) : "")+
    (this.record.effective_time_start==="" ? "Until "+hhmm_format(this.record.effective_time_end) : "")+
    (this.record.effective_time_start!=="" && this.record.effective_time_end!=="" ? hhmm_format(this.record.effective_time_start) + "&#8201;-&#8201;" + hhmm_format(this.record.effective_time_end) : "") +
    "</b><br />";
};

cal_events.prototype.display_title = function(){
  return "<a href=\""+this.record.path+"\" target=\"_blank\"><b>"+this.record.title+"</b></a><br style='clear:both'/>";
};

cal_events.prototype.display = function(can_edit){
  var html, i, title;
  html = "";
  if (can_edit){
    html+=
      "<div class='admin_toolbartable'>"+
      "<img class='b fl' src='"+base_url+"img/sysimg/icon_toolbar_end_left.gif' style='border:0;padding-left:2px;padding-top:1px;height:16px;width:6px;' alt='|'/>"+
      "<a class='TI' ref=\"/details/events/?ID=&amp;"+
      "YYYY="+this.YYYY+"&amp;MM="+this.MM+"&amp;DD="+this.DD+"\" " +
      "onclick=\"details('events','','680','800','','','','effective_date_start="+this.YYYY+'-'+this.MM+'-'+this.DD+"&effective_date_end="+this.YYYY+'-'+this.MM+'-'+this.DD+"');return false;\" " +
      "title=\"Add Event&hellip;\">" +
      "<img class=\"toolbar_icon\" src=\""+base_url+"img/spacer\"" +
      " alt=\"\" title=\"\"" +
      " style=\"width:11px;background-position:-1188px 0px;margin:0 5px 0 0;\" /></a>"+
      "<div class='fl' style='padding:2px'>Add new event for "+this.YYYYMMDD+"&hellip;</div>"+
      "<img class='b fl' src='"+base_url+"img/color/404080' style='width:1px;height:20px;' alt=''/>"+
      "</div><div class='clr_b' style='height:0;width:0;overflow:hidden'>&nbsp;</div>";
  }
  if (this.json.length===0){
    html+= "<div><b>Sorry!</b><br />There are no events for that date</div>";
  }
  else {
    title = site_title+": Events for "+this.YYYY+'-'+this.MM+'-'+this.DD;
    html+=
      "<a href='#' onclick=\"javascript:print_zone('popup_form_content_inner','"+title+"');return false;\">Print</a>"+
      "<h1 style='margin:0'>"+title+"</h1>"+
      "<div id='popup_form_content_inner' style='height:"+(this.height-(can_edit ? 94 : 78))+"px;overflow:auto'>";
    for (i=0; i<this.json.length; i++){
      this.record =   this.json[i];
      html+=
        "<div>\n"+
        this.display_shared()+
        this.display_title()+
        this.display_times()+
        this.display_icon()+
        this.display_content()+
        "</div>"+
        "<br class='clr_b' />"+
        (i!==(this.json.length-1) ? "<hr />" : "");
    }
    html+= "</div>";
  }
  $('#'+this.target)[0].innerHTML = html;
};

function cal_goto(offset) {
  var d, MM, YYYY;
  YYYY =        geid_val('YYYY');
  MM =		    geid_val('MM');
  if (offset){
    d =    new Date(YYYY,MM,'01');
    d.setMonth(d.getMonth()+offset-1);
  }
  else {
    d =    new Date();
  }
  YYYY =    ''+d.getFullYear();
  MM =      ''+(d.getMonth()+1);
  MM =      (MM.length===1 ? '0'+MM : MM);
  geid_set('YYYY',YYYY);
  geid_set('MM',MM);
  geid('form').submit();
}

function cal_list(YYYYMMDD,isAdmin){
  var ce=new cal_events(YYYYMMDD);
  ce.draw(isAdmin);
}

function cal_setup() {
  $('.cal_control').mouseover(function(){ this.className='cal_control_over';});
  $('.cal_control').mousedown(function(){ this.className='cal_control_down';});
  $('.cal_control').mouseout(function(){ this.className='cal_control';});
}

function challenge_password(title,type,has_history,previous_url,current_url){
  var h =
    "<div id='challenge_password'>\n"+
    "<h1>"+title+"</h1>\n"+
    "<div class='css3 challenge_password_outer'>\n"+
    "<p>The "+type+" you just requested requires a password.<br />\n"+
    "Please type a valid password, then click <b>Sign In</b>.</p>\n"+
    "<div class='css3 challenge_password_inner'>\n"+
    "<label for='cpw'>Password</label>\n"+
    "<input type='password' id='cpw' onkeypress=\"keytest_enter_transfer(event,\'cpw_s\')\" /><br /><br />\n"+
    "<input type='button' id='cpw_s' value='Sign In'"+
    " onclick='challenge_password.submit(\""+current_url+"\","+geid_val('cpw')+")'/>\n"+
    (has_history ? "<input type='button' value='Cancel' onclick='challenge_password.cancel(\""+previous_url+"\")' />\n" : "")+
    "</div>\n"+
    (has_history ? "<p>(If you don't have a valid password, choose <b>Cancel</b>)</p>\n" : '')+
    "</div>\n"+
    "</div>\n"+
    "</div>";
  popup_dialog('Password Required',h,400,300,'','');
  geid('cpw').focus();
  return false;
}

challenge_password.cancel = function(previous_url){
  document.location=previous_url;
}

challenge_password.submit = function(current_url,password){
  window.focus();
  $.ajax({
    data: {command: 'challenge_password',challenge_password:geid_val('cpw')},
    type: 'POST',
    url:  current_url
  })
  .done(function(transport){
    var result;
    result =  transport;
    if (result=='0'){
      alert('Incorrect Password');
      geid('cpw').focus();
      geid('cpw').select();
    }
    else {
      document.location=current_url;
    }
  });
  return false;
}

function char_counter(input,max,countID) {
  var _count = geid(countID);
  if (input.value.length>max) {input.value = input.value.substr(0,max); }
  _count.innerHTML = max - input.value.length + ' character' + (input.value.length===max-1 ? '' : 's')+' left';
}

function column_select(reportID,val,fn){
  var element, elements, find, id, out;
  elements = geid('form').elements;
  for (var i=0;  i<=elements.length; i++){
    element = elements[i];
    if (element && element.type==='checkbox' && element.id){
      find = 'row_select_'+reportID+'_';
      if (element.id.substr(0,find.length)==find) {
        element.checked = val;
      }
    }
  }
  if (typeof fn =='function'){
    fn();
  }
  else {
    selected_operation_enable(reportID);
  }
}

function comment_get_count(mode,ID) {
  include(base_url+'?command=comment&mode='+mode+'&ID='+ID+'&submode=get_count&rnd='+Math.random(),'comment_count');
}

function comment_delete(mode,ID,commentID) {
  var obj, xFn;
  window.focus();
  xFn = function() { comment_get_count(mode,ID); };
  include(base_url+'?command=comment&mode='+mode+'&ID='+ID+'&submode=delete&commentID='+commentID,'comment_'+commentID,xFn);
  obj = geid('comment_'+commentID);
  obj.parentNode.removeChild(obj);
}

function comment_mark(mode,ID,commentID,status) {
  var post_vars, xFn;
  window.focus();
  post_vars = "comment_approved="+status;
  xFn = function() { comment_get_count(mode,ID); };
  ajax_post(base_url+'?command=comment&mode='+mode+'&ID='+ID+'&submode=mark&commentID='+commentID+'&rnd='+Math.random(),'comment_'+commentID,post_vars,xFn);
  return false;
}

function comment_show_all(mode,ID) {
  include(base_url+'?command=comment&mode='+mode+'&ID='+ID+'&submode=show_all','comments_list');
}

function comment(mode,ID,submode,commentID) {
  var abort, post_vars, pw, xFn;
  pw = "<img class='fl' src='"+base_url+"img/sysimg/progress_indicator.gif' width='16' height='16' alt='Please wait...'>" +
    "<div class='fl' style='color:#808080'><em>&nbsp;Loading... Please Wait</em></div><div class='clr_b'></div>";
  switch (submode){
    case "cancel":
      geid('comment_button_cancel').disabled=true;
      geid('comment_button_submit').disabled=true;
      if (geid_val('comment_text') + geid_val('captcha_key') === '' || confirm('Really cancel? Your changes will be lost.')){
        window.focus();
        geid('comment_new').innerHTML="<p><a href=\"javascript:void comment('"+mode+"','"+ID+"','new')\">Add Comment</a></p>";
      }
      else {
        geid('comment_button_cancel').disabled=false;
        geid('comment_button_submit').disabled=false;
      }
      return false;
    case "delete":
      return comment_delete(mode,ID,commentID);
    case "edit":
      window.focus();
      xFn = function() { comment_get_count(mode,ID); };
      include(base_url+'?command=comment&mode='+mode+'&ID='+ID+'&submode='+submode+'&commentID='+commentID,'comment_'+commentID,xFn);
      return false;
    case "mark_approved":
      return comment_mark(mode,ID,commentID,'approved');
    case "mark_hidden":
      return comment_mark(mode,ID,commentID,'hidden');
    case "mark_pending":
      return comment_mark(mode,ID,commentID,'pending');
    case "mark_spam":
      return comment_mark(mode,ID,commentID,'spam');
    case "new":
      window.focus();
      geid('comment_new').innerHTML=pw;
      include(base_url+'?command=comment&mode='+mode+'&ID='+ID+'&submode='+submode,'comment_new');
      return false;
    case "post":
      window.focus();
      geid('comment_button_cancel').disabled=true;
      geid('comment_button_submit').disabled=true;
      abort = false;
      geid('th_comment_name').style.color =	(geid_val('comment_name')==='' ? '#ff0000' : '');
      geid('th_comment_email').style.color =	(geid_val('comment_email')==='' ? '#ff0000' : '');
      geid('th_comment_text').style.color =	(geid_val('comment_text')==='' ? '#ff0000' : '');
      geid('th_captcha_key').style.color =	(geid_val('captcha_key')==='' ? '#ff0000' : '');
      if (geid_val('comment_name')==='' || geid_val('comment_email')==='' || geid_val('comment_text')==='' || geid_val('captcha_key')==='') {
        abort=true;
      }
      if (abort){
        alert('Missing fields');
        geid('comment_button_cancel').disabled=false;
        geid('comment_button_submit').disabled=false;
      }
      else {
        window.focus();
        post_vars =
          "comment_name="+geid_val('comment_name')+
          "&comment_email="+geid_val('comment_email')+
          "&comment_url="+geid_val('comment_url')+
          "&comment_text="+geid_val('comment_text')+
          "&captcha_key="+geid_val('captcha_key');
        xFn =
          function() {
            comment_show_all(mode,ID);
          };
        ajax_post(base_url+'?command=comment&mode='+mode+'&ID='+ID+'&submode='+submode,'comment_new',post_vars,xFn);
      }
      return false;
    case "save":
      window.focus();
      abort = false;
      geid('th_comment_name').style.color =	(geid_val('comment_name')==='' ? '#ff0000' : '');
      geid('th_comment_email').style.color =	(geid_val('comment_email')==='' ? '#ff0000' : '');
      geid('th_comment_text').style.color =	(geid_val('comment_text')==='' ? '#ff0000' : '');
      if (geid_val('comment_name')==='' || geid_val('comment_email')==='' || geid_val('comment_text')==='') {
        abort=true;
      }
      if (abort){
        alert('Missing fields');
      }
      else {
        window.focus();
        post_vars =
          "comment_approved="+geid_val('comment_approved')+
          "&comment_name="+geid_val('comment_name')+
          "&comment_email="+geid_val('comment_email')+
          "&comment_url="+geid_val('comment_url')+
          "&comment_text="+geid_val('comment_text');
        xFn = function() { comment_show_all(mode,ID); };
        ajax_post(base_url+'?command=comment&mode='+mode+'&ID='+ID+'&submode='+submode+'&commentID='+commentID+'&rnd='+Math.random(),'comment_'+commentID,post_vars,xFn);
      }
      return false;
    case "show":
      window.focus();
      include(base_url+'?command=comment&mode='+mode+'&ID='+ID+'&submode='+submode+'&commentID='+commentID+'&rnd='+Math.random(),'comment_'+commentID);
      return false;
  }
  return false;
}

function community_embed(title,url,preset){
  var b, c, h, i, xtitle
  xtitle = title.replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
  b = ('AMP,ASV,DAR,ESV,GW,HCSB,KJV,LEB,MESSAGE,NASB,NCV,NIV,NIRV,NKJV,NLT,TNIV,YLT').split(',');
  c = " onclick=\"community_embed_code('"+xtitle+"','"+url+"');return true;\"";
  h =
    "<div class='community_embedding'>"+
    "<div class='embed_help'>"+
    "<img src='"+base_url+"img/spacer' class='icons info' />"+
    "You can place live content from <b>"+title+"</b> right into your existing website.<br />"+
    "Customise your embed code using the controls below:"+
    "</div>"+
    "<b>Controls:</b><br />"+
    "<div class='embed_c'>"+
    "<label for='community_member_article'><b>Articles</b></label>"+
    "<input type='checkbox' id='community_member_article' class='cb' value='1'"+c+(preset==='articles' ? " checked='checked'" : "")+" />"+
    "<label for='community_member_events'><b>Events</b></label>"+
    "<input type='checkbox' id='community_member_events'  class='cb' value='1'"+c+(preset==='events' ? " checked='checked'" : "")+" />"+
    "<label for='community_member_news'><b>News</b></label>"+
    "<input type='checkbox' id='community_member_news'    class='cb' value='1'"+c+(preset==='news' ? " checked='checked'" : "")+" />"+
    "<label for='community_member_podcasts'><b>Sermons</b></label>"+
    "<input type='checkbox' id='community_member_podcasts' class='cb' value='1'"+c+(preset==='podcasts' ? " checked='checked'" : "")+" />"+
    "<label for='community_member_calendar'><b>Calendar</b></label>"+
    "<input type='checkbox' id='community_member_calendar' class='cb' value='1'"+c+(preset==='calendar' ? " checked='checked'" : "")+" />"+
    "<label for='community_member_limit'><b>Records per page</b></label>"+
    "<input type='text' id='community_member_limit' style='float:left;margin-right:10px;width: 20px' value='10' onchange=\"community_embed_code('"+xtitle+"','"+url+"');return true\" />"+
    "<label for='community_member_bible_version'><b>Bible version for Ref tagger</b></label>"+
    "<select id='community_member_bible_version' style='float:left;margin-right:10px;' onchange=\"community_embed_code('"+xtitle+"','"+url+"');return true\">";
  for(i in b){
    h+= "  <option value='"+b[i]+"'"+(b[i]==='NIV' ? " selected='selected'" : '')+">"+b[i]+"</option>\n";
  }
  h+=
    "</select>\n"+
    "<br class='clear' /></div>"+
    "<label for='community_member_embed_head'><b>HTML Code - &lt;head&gt;</b><br />Paste this code into the &lt;head&gt; section of your web page.</label>"+
    "<textarea id='community_member_embed_head' class='h'></textarea>"+
    "<label for='community_member_embed_body'><b>HTML Code - &lt;body&gt;</b><br />Paste this code where you want the content to appear in your web page.</label>"+
    "<textarea id='community_member_embed_body' class='b'></textarea>"+
    "</div>";
  popup_dialog("Get live content content from "+xtitle+" for your website",h,900,550,'OK','Cancel');
  community_embed_code(xtitle,url);
  return false;
}

function community_embed_code(title,path){
  var base_url, bible_version, html_head, limit, num, a, c, e, n, s;
  a=geid('community_member_article').checked;
  c=geid('community_member_calendar').checked;
  e=geid('community_member_events').checked;
  n=geid('community_member_news').checked;
  p=geid('community_member_podcasts').checked;
  limit=geid('community_member_limit').value;
  bible_version=geid('community_member_bible_version').value;
  if (!(a||c||e||n||p)){
    geid_set('community_member_embed_head','');
    geid_set('community_member_embed_body','');
    return;
  }
  base_url = '//'+document.location.hostname;
  num = 1;
  html_head =
    "<script type=\"text/javascript\" src=\"\/\/ajax.googleapis.com\/ajax\/libs\/jquery\/1.8.0\/jquery.min.js\"><\/script>\n"+
    "<script type=\"text\/javascript\" src=\""+base_url+"\/sysjs\/ecc"+"\"></script>\n"+
    "<script type=\"text\/javascript\">\n"+
    "  ecc.bible_version='"+bible_version+"';\n"+
    (a ? "  ecc.load('"+path+"', 'articles', 'ecc_"+(num++)+"', "+limit+");\n" : "")+
    (e ? "  ecc.load('"+path+"', 'events',   'ecc_"+(num++)+"', "+limit+");\n" : "")+
    (n ? "  ecc.load('"+path+"', 'news',     'ecc_"+(num++)+"', "+limit+");\n" : "")+
    (p ? "  ecc.load('"+path+"', 'podcasts', 'ecc_"+(num++)+"', "+limit+");\n" : "")+
    (c ? "  ecc.load('"+path+"', 'calendar', 'ecc_"+(num++)+"');\n" : "")+
    "<\/script>\n"+
    "<style type=\"text\/css\">\n"+
    ".ecc   { float:left; padding:0.5%; margin:0.5%; overflow:auto; border:1px solid #888; background:#eff; }\n"+
    ".ecc_n { width:47%; height:200px; }\n"+
    ".ecc_w { width:96%; }\n"+
    "</style>\n";
  geid_set('community_member_embed_head',html_head.replace(/^\s+|\s+$/g,''));
  num = 1;
  html_body =
    (a ? "<div id='ecc_"+(num++)+"' class='ecc ecc_n'>Loading Articles from "+title+"...<\/div>\n" : "")+
    (e ? "<div id='ecc_"+(num++)+"' class='ecc ecc_n'>Loading Events from "+title+"...<\/div>\n" : "")+
    (n ? "<div id='ecc_"+(num++)+"' class='ecc ecc_n'>Loading News from "+title+"...<\/div>\n" : "")+
    (p ? "<div id='ecc_"+(num++)+"' class='ecc ecc_n'>Loading Podcasts from "+title+"...<\/div>\n" : "")+
    (c ? "<div id='ecc_"+(num++)+"' class='ecc ecc_w'>Loading Calendar for "+title+"...<\/div>\n" : "");
  geid_set('community_member_embed_body',html_body.replace(/^\s+|\s+$/g,''));
}

function csv_item_set(csv,value,add){
  var i, val_arr, new_arr, new_csv;
  val_arr = csv.split(',');
  new_arr =[];
  if (add){
    new_csv = csv_item_set(csv,value,false);
    new_arr = (new_csv!=='' ? new_csv.split(',') : []);
    new_arr.push(value);
  }
  else {
    for (i=0; i<val_arr.length; i++){
      if (val_arr[i]!=='' && val_arr[i]!==value){
        new_arr.push(val_arr[i]);
      }
    }
  }
  return new_arr.join(',');
}

function cursorGetSelectionEnd(o) {
  if (o.createTextRange) {
    var r = document.selection.createRange().duplicate();
    r.moveStart('character', -o.value.length);
    return r.text.length;
  }
  return o.selectionEnd;
}

function cursorGetSelectionStart(o) {
  if (o.createTextRange) {
    var r = document.selection.createRange().duplicate();
    r.moveEnd('character', o.value.length);
    if (r.text === ''){
      return o.value.length;
    }
    return o.value.lastIndexOf(r.text);
  }
  return o.selectionStart;
}

function cursorSetPosition(o,pos){
  if (o.createTextRange) {
    var range = o.createTextRange();
    range.collapse(true);
    range.moveEnd('character', pos);
    range.moveStart('character', pos);
    range.select();
    return true;
  }
  else if (o.setSelectionRange) {
    o.focus();
    o.setSelectionRange(pos,pos);
    return true;
  }
  return false;
}

function cursorIsAtEnd(o) {
  var end = o.value.length;
  if (cursorGetSelectionStart(o)===end){
    if (cursorGetSelectionEnd(o)<end){
      cursorSetPosition(o,end);
      return false;
    }
    return true;
  }
  return false;
}

function cursorIsAtStart(o) {
  if (cursorGetSelectionStart(o)===0){
    if (cursorGetSelectionEnd(o)>0){
      cursorSetPosition(o,0);
      return false;
    }
    return true;
  }
  return false;
}

function customform_draw_fees_overview(tax_regime_taxes_used,currency_symbol){
  var div_tax_total, div_tax_totals, i, lbl_width, out, tax;
  div_tax_total =  geid('div_tax_total');
  div_tax_totals = geid('div_tax_totals');
  lbl_width = parseInt(div_tax_totals.style.width)-70;
  out = '';
  if (tax_regime_taxes_used.length){
    div_tax_total.style.display='';
    div_tax_totals.style.display='';
    for(i=0; i<tax_regime_taxes_used.length; i++){
      tax = tax_regime_taxes_used[i];
      out+=
        "  <div style='line-height:18px;float:right;'>\n"+
        "    <div class='fl txt_r' style='width:"+lbl_width+"px;'>"+tax+"</div>\n" +
        "    <div class='fl txt_r' style='width:15px;'>"+currency_symbol+"</div>" +
        "    <div class='fl txt_r' style='padding-top:1px;'>" +
        "<input id=\"total_"+tax+"\" class='fl formField txt_r' " +
        "style='width: 50px;background-color: #f0f0f0;color: #404040;' type='text' onfocus='blur()' />" +
        "    </div>" +
        "  </div>" +
        "  <div class='clr_b'></div>\n";
    }
  }
  else {
    div_tax_total.style.display='none';
    div_tax_totals.style.display='none';
  }
  div_tax_totals.innerHTML = out;
}

function customform_get_tax_costs(product,tax_regime_arr,tax_regime_tax_columns_used,BCountryID,BSpID){
  var cost, column, i, idx, invert, out, price, tax_regime, rule, rule_total;
  var tax, test_place, test_place_arr;
  price  =       product.c;
  tax_regime  =  tax_regime_arr[product.tr];
  out =         [];
  out.total = 0;
  for(idx=0; idx<tax_regime_tax_columns_used.length; idx++){
    column = tax_regime.tax_names[tax_regime_tax_columns_used[idx]];
    out[column] = 0;
  }
  cost = 0;
  rule_total = 0;
  for(rule = 0; rule<tax_regime.tax_rules.length; rule++){
    rule_total = 0;
    for(tax=0 ; tax<tax_regime.tax_rules[rule].length ; tax++){
      test_place_arr = tax_regime.tax_rules[rule][tax].split(',');
      for(i=0;i<test_place_arr.length; i++){
        test_place=test_place_arr[i];
        invert = (test_place.substr(0,1)==='!' ? true : false);
        if (invert){
          test_place = test_place.substr(1);
          if (
            test_place !== BCountryID+'.'+BSpID &&
            test_place !== BCountryID+'.*' &&
            test_place !== '*.'+BSpID &&
            test_place !== '*.*'
          ){
            cost = (out.total+price)*tax_regime.tax_rates[tax];
            out[tax_regime.tax_names[tax]]=cost;
            rule_total+=cost;
          }
        }
        else{
          if(
            test_place === BCountryID+'.'+BSpID ||
            test_place === BCountryID+'.*' ||
            test_place === '*.'+BSpID ||
            test_place === '*.*'
          ){
            cost = (out.total+price)*tax_regime.tax_rates[tax];
            out[tax_regime.tax_names[tax]]=cost;
            rule_total+=cost;
          }
        }
      }
    }
    out.total+=rule_total;
  }
  return out;
}

function rgb2hex(rgb) {
  if (typeof rgb==='undefined' || typeof rgb==='null' || rgb===''){
    return '';
  }
  if (/^#[0-9A-F]{6}$/i.test(rgb)) return rgb;

  rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
  function hex(x) {
    return ("0" + parseInt(x).toString(16)).slice(-2);
  }
  if (rgb ===null){
    return '';
  }
  return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

customise_colours = {
  dialog: function(targets, lbl_title, lbl_ok, lbl_cancel){
    var current_color, current_bgcolor, entry_arr, i, j, js_ok, links,out, palette, parameters;
    var presets, presets, setup;
    out = "<div id='popup_form' style='padding:4px;' class='customiser'>";
    if (targets!=''){
      if (customise_colours_presets){
        out+= "<div style='background:#e0e0ff; padding:5px 2px; margin-bottom: 5px'><b>Presets:</b> [ ";
        presets = customise_colours_presets.split(',');
        links = []
        for (i=0; i<presets.length; i++){
          preset = presets[i].split('|');
          name = preset.shift();
          links.push(
            "<a href='#' onclick=\""+
            "return customise_colours.set('"+preset.join('|')+"');\""+
            " style='color:#0000ff'>"+
            name+"</a>"
          );
        }
        out+=links.join(' | ')+" ]</div>\n";
      }
      customise_colours.targets = targets.split(',');
      setup = (typeof customise_colours.initial === 'undefined' ? true : false);
      if (setup){
        customise_colours.initial = [];
        customise_colours.current = [];
      }
      for (i = 0; i < customise_colours.targets.length; i++) {
        parameters = customise_colours.targets[i].split('|');
        out+= "<label style='width:20em'><b>"+parameters[0]+"</b></label><br class='clear'/>";
        if (parameters[1]==='1'){
          current_bgcolor = rgb2hex($(parameters[0]).css('background-color'));
          if (setup){
            if (typeof customise_colours.initial[i]==='undefined'){
              customise_colours.initial[i] = {bgcolor: '0', color: '0' };
              customise_colours.current[i] = {bgcolor: '0', color: '0' };
            }
            customise_colours.initial[i].bgcolor = current_bgcolor;
            customise_colours.current[i].bgcolor = current_bgcolor;
          }
          out+=
            "<div class='fl'><label style='width:8em;text-align:right' for='color_s'>Background</label>"+
            "<input type='text' class='swatch' id='bgcolor_"+i+"' spellcheck='false' value='"+current_bgcolor+"'"+
            " onchange=\"geid_set('bgcolor_"+i+"_s',this.value);customise_colours.current["+i+"].bgcolor=this.value;"+
            "$('"+parameters[0]+"').css({backgroundColor: this.value})\""+
            "/>"+
            "<input type='text' class='spectrum' id='bgcolor_"+i+"_s' name='bgcolor_"+i+"_s' value='"+current_bgcolor+"'"+
            " onchange=\"$('#bgcolor_"+i+"').spectrum('set',this.value);customise_colours.current["+i+"].bgcolor=this.value;"+
            "$('"+parameters[0]+"').css({backgroundColor: this.value})\""+
            "/></div>";
        }
        if (parameters[2]==='1'){
          current_color = rgb2hex($(parameters[0]).css('color'));
          if (setup){
            if (typeof customise_colours.initial[i]==='undefined'){
              customise_colours.initial[i] = {bgcolor: '0', color: '0' };
              customise_colours.current[i] = {bgcolor: '0', color: '0' };
            }
            customise_colours.initial[i].color = current_color;
            customise_colours.current[i].color = current_color;
          }
          out+=
            "<div class='fl'><label style='width:8em;text-align:right' for='color_s'>Text</label>"+
            "<input type='text' class='swatch' id='color_"+i+"' spellcheck='false' value='"+current_color+"'"+
            " onchange=\"geid_set('color_"+i+"_s',this.value);customise_colours.current["+i+"].color=this.value;"+
            "$('"+parameters[0]+"').css({color: this.value });\"/>"+
            "<input type='text' class='spectrum' id='color_"+i+"_s' name='color_"+i+"_s' value='"+current_color+"'"+
            " onchange=\"$('#color_"+i+"').spectrum('set',this.value);customise_colours.current["+i+"].color=this.value;"+
            "$('"+parameters[0]+"').css({color: this.value})\""+
            "/></div>";
        }
        out+="<div style='clear:both; border-bottom: 1px solid #aaa; margin: 2px 0px; height:5px; overflow:hidden'>&nbsp;</div>";
      }
    }
    out+=
      "<div id='popup_buttons' style='padding-top:5px;margin:auto' class='txt_c'>"+
      "<input type='button' id='btn_reset' class='formButton' style='width:60px;' value='Reset'"+
      " onclick=\"customise_colours.reset(customise_colours.initial)\">"+
      " <input type='button' id='btn_save' class='formButton' style='width:60px;' value='Preview'"+
      " onclick=\"hidePopWin(null);\">"+
      " <input type='button' id='btn_save' class='formButton' style='width:60px;' value='Save'"+
      " onclick=\"customise_colours.save(customise_colours.current);\">"+
      "</div>";
    popup_dialog(lbl_title, out, 400, 400);
    for (i=0; i < customise_colours.targets.length; i++) {
      $('#bgcolor_'+i).spectrum({ allowEmpty:true, showPalette:true, preferredFormat: 'hex' });
      $('#color_'+i  ).spectrum({ allowEmpty:true, showPalette:true, preferredFormat: 'hex' });
    }
    return false;
  },
  reset: function(initial){
    for (i=0; i < initial.length; i++) {
      $('#bgcolor_'+i+'_s').val(initial[i].bgcolor).change();
      $('#color_'+i+'_s').val(initial[i].color).change();
    }
    hidePopWin(null);
  },
  save: function (current){
    var name, parameters, query;
    name = prompt('Type a name for this setting');
    if (name===null) {
        return;
    }
    parameters = []
    for (i=0; i < current.length; i++) {
      parameters[i] = current[i].bgcolor+'|'+current[i].color;
    }
    query = encodeURIComponent(name)+'|'+parameters.join('|');
    geid_set('command', 'customise_colours');
    geid_set('targetValue', query);
    geid('form').submit();
  },
  set: function(preset){
    var presets
    presets = preset.split('|')
    for (i=0; i < presets.length/2; i++) {
      if (presets[i*2]){
        $('#bgcolor_'+i+'_s').val(presets[i*2]).change();
      }
      if (presets[1+i*2]){
        $('#color_'+i+'_s').val(presets[1+i*2]).change();
      }
    }
    hidePopWin(null);
    return false;
  }

}

function date_selector_draw(base_id,date){
  var out = [], i, i_str;
  out.push(
    "<select class='formField' id='"+base_id+"_yyyy' onchange=\"date_selector_onchange('"+base_id+"')\">\n" +
    "  <option value=\"\"" + (date.length<4 ? " selected='selected'" : "") + ">----</option>\n"
  );
  for (i=_global_date_range_min.substr(0,4); i<=_global_date_range_max.substr(0,4); i++){
    out.push("  <option value=\""+i+"\"" + (date.length>=4 && date.substr(0,4)===i.toString() ? " selected='selected'" : "") + ">"+i+"</option>\n");
  }
  out.push("</select> - ");
  out.push(
    "<select class='formField' id='"+base_id+"_mm' onchange=\"date_selector_onchange('"+base_id+"')\">\n" +
    "  <option value=\"\"" + (date.length<7 ? " selected='selected'" : "") + ">--</option>\n"
  );
  for (i=1; i<=12; i++){
    i_str = lead_zero(i.toString(),2);
    out.push("  <option value=\""+i_str+"\"" + (date.length>=5 && date.substr(5,2)===i_str ? " selected='selected'" : "") + ">"+i_str+"</option>\n");
  }
  out.push("</select> - ");
  out.push(
    "<select class='formField' id='"+base_id+"_dd'>\n" +
    "  <option value=\"\"" + (date.length<10 ? " selected='selected'" : "") + ">--</option>\n"
  );
  for (i=1; i<=31; i++){
    i_str = lead_zero(i.toString(),2);
    out.push("  <option value=\""+i_str+"\"" + (date.length>=10 && date.substr(8,2)===i_str ? " selected='selected'" : "") + ">"+i_str+"</option>\n");
  }
  out.push("</select>");
  return (out.join(''));
}

function date_selector_onchange(base_id) {
  var dd, i, i_str, leap, max_dd, opt, yyyy;
  if (geid_val(base_id+'_yyyy')===''){
    geid_set(base_id+'_mm','');
  }
  if (geid_val(base_id+'_mm')===''){
    geid_set(base_id+'_dd','');
  }
  else {
    yyyy = parseFloat(geid_val(base_id+'_yyyy'));
    leap = ((yyyy%4===0) && (yyyy%100!==0)) || (yyyy%400===0);
    switch (parseFloat(geid_val(base_id+'_mm'))){
      case 4: case 6: case 9: case 11:
        max_dd = 30;
      break;
      case 2:
        max_dd = (leap ? 29 : 28);
      break;
      default:
        max_dd = 31;
      break;
    }
    dd = geid_val(base_id+"_dd");
    for (i=0; i< geid(base_id+'_dd').length; i++) {
      geid(base_id+'_dd').remove(i);
    }
    opt = new Option();
    opt.text = '--';
    opt.value = '';
    if (dd==='') {
      opt.selected = true;
    }
    geid(base_id+'_dd').options[0] = opt;
    for (i=1; i<=max_dd; i++) {
      i_str = lead_zero(i.toString(),2);
      opt = new Option();
      opt.text = i_str;
      opt.value = i_str;
      if (dd === i_str){
        opt.selected = true;
      }
      geid(base_id+'_dd').options[i] = opt;
    }
    if (parseFloat(dd)>max_dd){
      geid(base_id+"_dd").options[geid(base_id+"_dd").length-1].selected=true;
    }
  }
  geid(base_id+'_mm').disabled = (geid_val(base_id+'_yyyy')==='' ? true : false);
  geid(base_id+'_dd').disabled = (geid_val(base_id+'_mm')==='' ? true : false);
}

function div_toggle(id){
  var div = geid(id);
  if (!div){
    return false;
  }
  div.style.display=(div.style.display==='none' ? '' : 'none');
  return false;
}

// ************************************
// * Document Reader functions        *
// ************************************
function document_reader_get_page_name(page){
  if (typeof(doc.named_pages[page+1])!=='undefined'){
    return doc.named_pages[page+1];
  }
  if (typeof(doc.named_pages[-1*(doc.pages_total-page)])!=='undefined'){
    return doc.named_pages[-1*(doc.pages_total-page)];
  }
  if (page<0 || page>doc.pages_total) {
    return "&nbsp;";
  }
  if (doc.pages_per_image===1) {
    return (page+1-doc.number_offset);
  }
  var start = ((page - 1 )*doc.pages_per_image)+ doc.number_offset;
  var end =   start+doc.pages_per_image-1;
  return start+"-"+end;
}

function document_reader_get_page_prefix(page){
  if (typeof(doc.named_pages[page+1])!=='undefined'){
    return "";
  }
  if (typeof(doc.named_pages[-1*(doc.pages_total-page)])!=='undefined'){
    return "";
  }
  if (page<0 || page>doc.pages_total) {
    return "";
  }
  if (doc.pages_per_image===1) {
    return "Page ";
  }
  return "Pages ";
}

function document_reader_goto_page(page) {
  var i, lbl_back, lbl_back_prefixed, lbl_next, lbl_next_prefixed, link_back, link_next;
  var m_1, m_2, m_3;
  m_1 = geid('mag_1');
  m_2 = geid('mag_2');
  m_3 = geid('mag_3');
  lbl_back =          document_reader_get_page_name(page-1);
  lbl_back_prefixed = document_reader_get_page_prefix(page-1)+lbl_back;
  link_back =	     "<a href='#' title='View "+lbl_back_prefixed+"' onclick='return document_reader_goto_page("+(page-1)+")'>&lt;</a>";
  lbl_next =          document_reader_get_page_name(page+1);
  lbl_next_prefixed = document_reader_get_page_prefix(page+1)+lbl_next;
  link_next =        "<a href='#' title='View "+lbl_next_prefixed+"' onclick='return document_reader_goto_page("+(page+1)+")'>&gt;</a>";
  m_1.innerHTML = (page===0 ? '&nbsp;' : link_back);
  m_2.innerHTML = document_reader_get_page_name(page);
  m_3.innerHTML = (page===doc.pages_total-1 ? '&nbsp;' : link_next);
  for (i=0; i<doc.pages_total; i++){
    geid('p'+(i)).style.backgroundColor='#ffffff';
  }
  geid('p'+(page)).style.backgroundColor='#ffff00';
  geid('img_doc').src = (page===0 ? doc.cover_file : doc.pages_filepath+(page+1)+doc.pages_filetype);
  return false;
}


function document_reader(div) {
  var out, i, link_title_prefix, p, page, pages;
  out = [];
  p =
    (window.location.hash.length && parseFloat(window.location.hash.substr(1)) ?
       parseFloat(window.location.hash.substr(1)-1)
     : 0
    );
  pages = doc.pages_total;
  out.push(
    "<div style='margin:0 auto;' class='txt_c'>" +
    "  <div style='margin:0 auto;width:160px;height:30px;' class='txt_c clr_b'>\n" +
    "    <div id='mag_1' class='fl' style='margin:5px;width:15px;background-color:#c0c0c0;display:inline;font-weight:bold'></div>\n" +
    "    <div id='mag_2' class='fl' style='margin:5px;width:100px;background-color:#c0c0c0;font-weight:bold'></div>\n" +
    "    <div id='mag_3' class='fl' style='margin:5px;width:15px;background-color:#c0c0c0;font-weight:bold'></div>\n" +
    "  </div>" +
    "  <div class='clr_b'></div>" +
    "[ "
  );
  for (i=0; i<pages; i++) {
    page = document_reader_get_page_name(i,pages);
    link_title_prefix = document_reader_get_page_prefix(i);
    out.push(
      "<a href='#' title='View "+link_title_prefix+page+"' onclick='return document_reader_goto_page("+i+")'>" +
      "<span id='p"+(i)+"'>"+page+"</span>" +
      "</a> "
    );
  }
  out.push(" ]</div>");
  out.push("<p class=\"txt_c\"><img id=\"img_doc\" border=\"1\" alt=\"\" src=\""+(doc.cover_file)+"\" /></p>");
  geid(div).innerHTML = out.join('');
  document_reader_goto_page(p,pages);
}

function externalLinks() {
  var anchor, anchors, html, rel_arr, rel, attributes, i;
  // Ref: http://www.sitepoint.com/article/standards-compliant-world/3
  //      http://www.biblegateway.com/passage/?search=matthew+10:16
  // 'Try / Catch' Fix suggested by James Fraser to prevent hrefs with '%' crashing JS engine in IE
  if (!document.getElementsByTagName){
    return;
  }
  anchors = document.getElementsByTagName("a");
  for (i=0; i<anchors.length; i++) {
    anchor = anchors[i];
    try{
      if (typeof anchor.getAttribute("href")==='string' && anchor.getAttribute("href") && anchor.getAttribute("rel") === "external"){
        anchor.target = "_blank";
      }
      if (anchor.className==='rss-item') {
        anchor.target = "_blank";
      }
      if (anchor.className==='iframe'){
        rel_arr = anchor.rel.split('|');
        html =
            "<iframe" +
            " src=\""+anchor.href+"\"" +
            " title=\"" + anchor.innerHTML + "\"";
        for(i=0; i<rel_arr.length; i++){
          rel = rel_arr[i].split('=');
          html += " "+rel[0]+"=\""+rel[1]+"\"";
        }
        html += "></iframe>";
        anchor.outerHTML = html;
      }
      if (anchor.getAttribute("href") && anchor.getAttribute("rel") === "disabled"){
        anchor.disabled = true;
      }
    }
    catch (e) {
      alert("This link has an invalid href:\n"+anchor.outerHTML);
    }
  }
}

function flowplayer_popup(page,ID){
  popWin(base_url+page+'?ID='+ID,'video_player_'+ID,'location=0,status=0,scrollbars=0,resizable=1',470,385,1);
}

function flowplayer_video(div,title,thumbnail,video_id,video,preroll_id,preroll,trigger_category,trigger_person){
  var site = window.location.protocol+'//'+window.location.host+'/';
  var playlist = [];
  if (thumbnail) {
    playlist.push({ url: site+thumbnail, autoPlay: true });
  }
  if (typeof preroll!=="undefined" && preroll!=='') {
    playlist.push({
      url: site+preroll,
      autoPlay: false,
      title: 'Preroll',
      onBeforeSeek:
        function(clip){
          return false;
        },
      onBeforeFinish:
        function(clip){
          post_vars = {
            player_event:   'preroll_finished',
            ID:             video_id,
            prerollID:      preroll_id
          };
          if (typeof trigger_category!=="undefined" && trigger_category!==''){
            post_vars.category =    trigger_category;
          }
          if (typeof trigger_person!=="undefined" && trigger_person!==''){
            post_vars.personID =    trigger_person;
          }
          $.ajax({
            data: post_vars,
            type: 'POST',
            url: document.location.pathname
          });
        }
    });
    playlist.push({url: site+video, autoPlay: true, title: title});
  }
  else {
    playlist.push({url: site+video, autoPlay: false, title: title});
  }
  flowplayer(
    div,
    { src: site+'flowplayer-3.1.5.swf', wmode: 'opaque' },
    {
      plugins: { apache_pseudostream: {url:site+'flowplayer.pseudostreaming-3.1.3.swf'}},
      clip:    { baseUrl:site+'uploads/processed',  provider: 'apache_pseudostream' },
      playlist: playlist
    }
  );
  flowplayer(div).load();
  return false;
}

function JQ_cumulativeScrollOffset(element) {
  var valueT = 0, valueL = 0;
  do {
    valueT += parseInt(element.offset().top)  || 0;
    valueL += parseInt(element.offset().left) || 0;
    element = element.parentNode;
  }
  while (element);
  return {0:valueL, 1:valueT, 'left':valueL, 'right':valueT};
}

function gallery_album_image_mouseover(obj,hover_size){
  var container, flip_lr, flip_tb, height, i, id, id_arr, img, item, left, pos;
  var scroll, top, trigger, width;
  gallery_album_image_mouseout();
  id = obj.parentNode.id;
  id_arr = obj.parentNode.id.split('_');
  img =    id_arr.pop();  list =   id_arr.join('_')+'_image_list';
  item =   false;
  for(i=0; i<window[list].length; i++){
    if (img == window[list][i].ID){
      item = window[list][i];
      break;
    }
  }
  if (item){
    trigger = $('#'+id);
    pos =    trigger.offset();
    scroll = JQ_cumulativeScrollOffset(trigger);
    height = trigger.height();
    width =  trigger.width();
    flip_lr =   pos.left>($(window).width()/2);
    left =      parseInt(pos.left+(flip_lr ? 0-hover_size-(width/2) : width+5))+'px';
    top =       scroll[1]+'px';
    container = $('<div id="hover_image" class="css3 gallery_album_hover_photo" style="left:'+left+';top:'+top+';width:'+hover_size+'px"></div>');
    if (item.title){
      $('<h2>'+item.title+'</h2>').appendTo(container);
    }
    if (item.image3){
      $('<img src="'+item.image3+'" class="css3" alt="" />').appendTo(container);
    }
    if (item.caption){
      $('<div class="css3">'+item.caption+'</div>').appendTo(container);
    }
    container.appendTo('body');
  }
}

function gallery_album_image_mouseout(){
  var zoom;
  zoom = $('#hover_image');
  if (typeof zoom[0]!='undefined'){
    document.body.removeChild(zoom[0]);
  }
}

function gallery_album_slideshow_loader(title,url,w,h){
  var html = "<div id='popup_form'><div style='padding:4px'>Loading...<\/div><\/div>";
  popup_dialog(title,html,w,h,'','Close');
  $.ajax({
    dataType: 'json',
    type: 'POST',
    url:  url+"?submode=gallery_slideshow"
  })
  .done(function(transport){
    var layer, json;
    layer = geid('popup_form');
    json = transport;
    layer.innerHTML=json.html;
    eval(json.js);
  });
}

function image_rotator(
  id,image_idx,controls_show,count_show,dwell_time,fade_time,image_arr,
  isAdmin,controls_size,caption_show,title_show,onchange,sequence,cover_image
){
  this.controls_show =  controls_show;
  this.count_show =     count_show;
  this.dwell_time =     dwell_time;
  this.fade_step =      1;
  this.fade_time =      fade_time;
  this.id =             id;
  this.image_arr =      image_arr;
  this.image_idx =      image_idx;
  this.isAdmin =        isAdmin;
  this.playing =        true;
  this.timer_controls = false;
  this.timer_dwell =    false;
  this.timer_fade =     false;
  this.controls_size =  (typeof controls_size!=='undefined' ? controls_size : 'l');
  this.caption_show =   (typeof caption_show!=='undefined' ? caption_show : 'l');
  this.title_show =     (typeof title_show!=='undefined' ? title_show : 'l');
  this.onchange =       (typeof onchange!=='undefined' ? onchange : '');
  this.sequence =       (typeof sequence==='object' ? sequence.toString().split(',') : false);
  this.cover_image =    (typeof cover_image!=='undefined' ? cover_image : 0);
}

image_rotator.prototype.do_setup = function(){
  var i, j, self, tmp;
  self = this;
  if (self.sequence){
    tmp = [];
    for(i=0; i<self.sequence.length; i++){
      for (j=0; j<self.image_arr.length; j++){
        if (parseInt(self.sequence[i])===parseInt(self.image_arr[j].ID)){
          tmp.push(self.image_arr[j]);
          break;
        }
      }
    }
    self.image_arr=tmp;
  }
  self.div =            geid(self.id);
  self.div_mask =       geid(self.id+'_mask');
  self.div_controls =   geid(self.id+'_controls');
  self.div_content =    geid(self.id+'_content');
  self.status =         geid(self.id+'_status');
  self.img1 =           geid(self.id+'_1');
  self.img2 =           geid(self.id+'_2');
  self.started =        false;
  self.clear_width_and_height();
  self.controls_setup();
  self.set_image();
  self.setup_cm();
  self.do_dwell();
};

image_rotator.prototype.clear_width_and_height = function(){
  // Width and height attributes were only ever set to aid in SEO ranking
  // but mess things up as images with different aspect ratios are shown
  var self =        this;
  self.img1.removeAttribute('width');
  self.img1.removeAttribute('height');
  self.img2.removeAttribute('width');
  self.img2.removeAttribute('height');
}

image_rotator.prototype.do_dwell = function(){
  var self =        this;
  if (self.playing){
    if (self.timer_dwell){
      window.clearTimeout(self.timer_dwell);
    }
    if (self.timer_fade){
      window.clearTimeout(self.timer_fade);
    }
    self.timer_dwell =   window.setTimeout(function(){self.do_fade_step();},self.dwell_time*1000);
  }
};

image_rotator.prototype.set_image = function(){
  var self =        this;
  if (self.cover_image && !self.started){
    self.started = true;
    self.image_idx--;
    return;
  }
  if (!self.image_arr[self.image_idx].enabled){
    self.status.style.backgroundImage="url("+base_url+"img/sysimg/disabled.png)";
    self.status.style.opacity = 0.5;
    self.status.style.filter = 'alpha(opacity=50)';
  }
  if (self.image_arr[self.image_idx].enabled){
    self.status.style.backgroundImage='';
    self.status.style.opacity = '';
    self.status.style.filter = '';
  }
  if(self.img2){
    self.img2.style.opacity = 0.1;
    self.img2.style.filter = 'alpha(opacity=10)';
    if (typeof self.image_arr[self.image_idx]!== 'undefined' && typeof self.image_arr[self.image_idx].image !== 'undefined'){
      self.img2.src = self.image_arr[self.image_idx].image;
    }
  }
  self.div_mask.title = self.image_arr[self.image_idx].title;
  if (self.div_content){
    self.div_content.innerHTML =
      (self.title_show || self.count_show ?
        "<h2>" +
        (self.title_show ? "<span class='fader_title fl'>"+self.image_arr[self.image_idx].title+"</span>" : "") +
        (self.count_show ? "<span class='fader_count fr' style='font-weight:normal'>("+(self.image_idx+1)+" of "+self.image_arr.length+")</span>" : "") +
        "</h2>\n"
      :
        ""
      ) +
      (self.caption_show ? "<div class='fader_caption clr_b'>"+self.image_arr[self.image_idx].caption+"</div>" : "");
  }
  if (self.image_arr[self.image_idx].url===''){
    self.div_mask.onclick=function(){};
  }
  else if (self.image_arr[self.image_idx].url_popup===1){
    self.div_mask.onclick=function(){
      popWin(self.image_arr[self.image_idx].url,self.id,'location=1,status=1,scrollbars=1,resizable=1',720,400,1);
    };
  }
  else {
    self.div_mask.onclick=function(){
      window.location=self.image_arr[self.image_idx].url;
    };
  }
  if (self.timer_fade){
    window.clearTimeout(self.timer_fade);
  }
  if (self.onchange){
    eval(self.onchange);
  }
  self.fade_step++;
  self.timer_fade =   window.setTimeout(function(){self.do_fade_step();}, self.fade_time*100);
  self.setup_cm();
};

image_rotator.prototype.setup_cm = function(){
  var self =            this;
  if (self.isAdmin){
    self.div_mask.onmouseover=function(){
      var img = self.image_arr[self.image_idx];
      var cm =  (img.subtype.replace('-','_')!=='' ? img.subtype.replace('-','_') : 'gallery_image');
      if(!CM_visible('CM_'+cm)){
        _CM.category = img.category;
        _CM.enabled = img.enabled;
        _CM.source = self.id;
        _CM.type = cm;
        _CM.ID = img.ID;
        _CM_text[0] = '&quot;'+img.title+'&quot;';
        _CM_ID[2] = img.parentID;
        _CM_text[2] = '&quot;'+img.parentTitle+'&quot;';
      }
    };
    self.div_mask.onmouseout=function(){
      _CM.type='';
    };
  }
};

image_rotator.prototype.btn_first = function(){
  var self =        this;
  self.image_idx =  0;
  self.set_image();
  return false;
};

image_rotator.prototype.btn_last = function(){
  var self =        this;
  self.image_idx =  self.image_arr.length-1;
  self.set_image();
  return false;
};

image_rotator.prototype.btn_next = function(){
  var self =        this;
  self.image_idx++;
  if (self.image_idx>=self.image_arr.length){
    self.image_idx =  0;
  }
  self.set_image();
  return false;
} ;

image_rotator.prototype.btn_pause_play = function(){
  var self =        this;
  var btn =         geid(self.id+'_pause_play');
  var anchor =      geid(self.id+'_pause_play_anchor');
  if (self.playing){
    self.playing = false;
    btn.title='Play';
    btn.style.backgroundPosition=(-3*self.size)+'px 0px';
    anchor.onmouseover=function(){geid(self.id+'_pause_play').style.backgroundPosition=(-3*self.size)+'px 0px';};
    anchor.onmouseout=function(){geid(self.id+'_pause_play').style.backgroundPosition=(-3*self.size)+'px -'+self.size+'px';};
  }
  else{
    self.playing = true;
    btn.title='Pause';
    btn.style.backgroundPosition=(-2*self.size)+'px 0px';
    anchor.onmouseover=function(){geid(self.id+'_pause_play').style.backgroundPosition=(-2*self.size)+'px 0px';};
    anchor.onmouseout=function(){geid(self.id+'_pause_play').style.backgroundPosition=(-2*self.size)+'px -'+self.size+'px';};
    self.do_dwell();
  }
  return false;
};

image_rotator.prototype.btn_previous = function(){
  var self =        this;
  self.image_idx--;
  if (self.image_idx<0){
    self.image_idx =  self.image_arr.length-1;
  }
  self.set_image();
  return false;
};

image_rotator.prototype.controls_setup = function(){
  var html, i, self, state, states;
  self =        this;
  if (!self.controls_show){
    return;
  }
  switch(self.controls_size){
    case 's':
      self.size = 25;
      self.img = "playback_controls_s.png";
    break;
    case 'm':
      self.size = 40;
      self.img = "playback_controls_m.png";
    break;
    case 'l':
      self.size = 50;
      self.img = "playback_controls.png";
    break;
  }
  self.div.onmouseover = function(){
    if (self.timer_controls){ window.clearTimeout(self.timer_controls);  }
    self.controls_set_opacity(100);
  };
  self.div.onmouseout = function(){
    if (self.timer_controls){ window.clearTimeout(self.timer_controls); }
//    self.playing=true;
    self.timer_controls = window.setTimeout(
      function(){ self.controls_set_opacity(0);},250
    );
  };
  html = "";
  states = [
    {offset: -0*self.size, id: 'first',      title: 'First',    onclick: "return obj_"+self.id+".btn_first()" },
    {offset: -1*self.size, id: 'previous',   title: 'Previous', onclick: "return obj_"+self.id+".btn_previous()" },
    {offset: -2*self.size, id: 'pause_play', title: 'Pause',    onclick: "return obj_"+self.id+".btn_pause_play()" },
    {offset: -4*self.size, id: 'next',       title: 'Next',     onclick: "return obj_"+self.id+".btn_next()" },
    {offset: -5*self.size, id: 'last',       title: 'Last',     onclick: "return obj_"+self.id+".btn_last()" }
  ];
  for(i=0; i<states.length; i++){
    state = states[i];
    html+=
      "<a id=\""+self.id+"_"+state.id+"_anchor\" href=\"#\"" +
      " onmouseover=\"geid('"+self.id+"_"+state.id+"').style.backgroundPosition='"+state.offset+"px 0px';\"" +
      " onmouseout=\"geid('"+self.id+"_"+state.id+"').style.backgroundPosition='"+state.offset+"px -"+self.size+"px';\"" +
      " onclick=\""+state.onclick+"\"" +
      ">" +
      "<img id=\""+self.id+"_"+state.id+"\" src=\""+base_url+"img/spacer\"" +
      " alt=\""+state.title+"\" title=\""+state.title+"\"" +
      " style=\"width:"+self.size+"px;height:"+self.size+"px;margin-right:5px;display:block;float:left;border:0;" +
      "background:url("+base_url+"img/sysimg/"+self.img+")  "+state.offset+"px  -"+self.size+"px;\" />" +
      "</a>";
  }
  self.div_controls.innerHTML = html;
};

image_rotator.prototype.controls_set_opacity = function(percent){
  var self;
  self =        this;
  if (percent===100){
    self.div_controls.style.opacity='';
    self.div_controls.style.filter='';
  }
  else {
    self.div_controls.style.opacity=(percent/100);
    self.div_controls.style.filter='alpha(opacity='+percent+')';
  }
};

image_rotator.prototype.do_fade_step = function(){
  var self =        this;
  switch(self.fade_step){
    case 1:
      self.image_idx++;
      if (self.image_idx === self.image_arr.length) {
        self.image_idx = 0;
      }
      self.set_image();
    break;
    case 10:
      if(self.img2){
        self.img1.src =             self.img2.src;
        self.img2.style.opacity =   0;
        self.img2.style.filter =    'alpha(opacity=0)';
      }
      self.fade_step=1;
      if (self.timer_dwell){
        window.clearTimeout(self.timer_dwell);
      }
      if (self.playing){
        self.timer_dwell =   window.setTimeout(function(){self.do_dwell();}, self.dwell_time*1000);
      }
    break;
    default:
      if (self.fade_step>10){
        self.fade_step=0;
        return;
      }
      if(self.img2){
        self.img2.style.opacity = self.fade_step / 10;
        self.img2.style.filter = 'alpha(opacity=' + self.fade_step * 10 + ')';
      }
      self.fade_step++;
      if (self.timer_fade){
        window.clearTimeout(self.timer_fade);
      }
      self.timer_fade =   window.setTimeout(function(){self.do_fade_step();}, self.fade_time*100);
    break;
  }
};

function inline_signin_hide_msg(){
  var div = geid('topbar_signin_msg');
  if (div){
    div.style.display='none';
  }
}

function list_set_onclick_items(div){
  var container, elements, i, anchors;
  container = geid(div);
  if (!container){
    return;
  }
  elements = container.getElementsByTagName('li');
  for(i=0; i<elements.length; i++){
    anchors = elements[i].getElementsByTagName('a');
    if (anchors && typeof anchors[0]!=='undefined' && typeof anchors[0].title!=='undefined'){
      elements[i].title = anchors[0].title;
    }
    if (anchors && typeof anchors[0]!=='undefined' && typeof anchors[0].href!=='undefined'){
      list_set_onclick_items_fn(elements[i],anchors[0].href);
    }
    if (anchors && typeof anchors[0]!=='undefined' && typeof anchors[0].onmouseover!=='undefined'){
      list_set_onmouseover_items_fn(elements[i],anchors[0].onmouseover);
    }
    if (anchors && typeof anchors[0]!=='undefined' && typeof anchors[0].onmouseout!=='undefined'){
      list_set_onmouseout_items_fn(elements[i],anchors[0].onmouseout);
    }
  }
}

function list_set_onclick_items_fn(element,href){
  addEvent(element,'click',function(e){document.location=href;return false;});
}

function list_set_onmouseover_items_fn(element,action){
  addEvent(element,'mouseover',action);
}

function list_set_onmouseout_items_fn(element,action){
  addEvent(element,'mouseout',action);
}

function list_folder_expander(divID,show_folder_icons){
  this.divID = divID;
  this.show_folder_icons = show_folder_icons;
}

list_folder_expander.prototype.init = function(dur){
  // Based on http://willworkforart.net/demos/unobtrusive-navigation
  var href, i, navSub, self, toggleImage;
  self = this;
  if(!document.getElementById || !document.getElementsByTagName || !document.createElement){
    return;
  }
  this.icon_width =             (this.show_folder_icons ? 27 : 11);
  this.icon_height =            16;
  this.icon_closed =            '-4822px 0';
  this.icon_open =              '-4849px 0';
  this.effect_duration =        (typeof dur!='undefined' ? dur : 0.5);
  this.navigation =             geid(this.divID);
  this.navigation_scrollbar =   geid(this.divID+'_scrollbar');
  navSub = this.navigation.getElementsByTagName('ul');
  for (i=0; i<navSub.length; i++){
	toggleImage = document.createElement('img');
    toggleImage.setAttribute('class', 'icons');
	toggleImage.setAttribute('src', base_url+'img/spacer');
	toggleImage.setAttribute('style', 'cursor:pointer;width:'+this.icon_width+'px;height:'+this.icon_height+'px;background-position:'+this.icon_closed);
	toggleImage.onclick = function(){ self.toggleNav(this); };
	navSub[i].parentNode.insertBefore(toggleImage, navSub[i].parentNode.firstChild);
	navSub[i].style.display='none';
	navSub[i].parentNode.className = 'expandable';
  }
  for (i=0; i<navSub.length; i++){
    if (navSub[i].parentNode.getElementsByTagName('b').length){
      navSub[i].parentNode.getElementsByTagName('img')[0].onclick();
    }
  }
};

list_folder_expander.prototype.collapse_all = function(){
  var allImages, i, navigationULs;
  self = this;
  navigationULs = self.navigation.getElementsByTagName('ul');
  allImages = self.navigation.getElementsByTagName('img');
  for (i = 0; i < navigationULs.length; i++) {
    if (navigationULs[i].style.display!='none'){
      $(navigationULs[i]).slideUp(
        self.effect_duration*1000,
        function(){
          allImages[i].style.backgroundPosition=self.icon_closed;
          self.navigation_scrollbar.scrollTop=0;
        }
      );
    }
  }
};

list_folder_expander.prototype.expand_all = function(){
  var allImages, i, navigationULs;
  self = this;
  navigationULs = self.navigation.getElementsByTagName('ul');
  allImages = self.navigation.getElementsByTagName('img');
  for (i = 0; i < navigationULs.length; i++) {
    if (navigationULs[i].style.display=='none'){
      $(navigationULs[i]).slideDown(
        self.effect_duration*1000,
        function(){
          allImages[i].style.backgroundPosition=self.icon_open;
        }
      );
    }
  }
};

list_folder_expander.prototype.toggleNav = function(whichOne){
  var self, theParent, theParentULs, theParentImage;
  self = this;
  theParent = whichOne.parentNode;
  theParentULs = theParent.getElementsByTagName('ul');
  theParentImage = theParent.getElementsByTagName('img');
  if (theParentULs[0].style.display == 'none') {
    $(theParentULs[0]).slideDown(
      self.effect_duration*1000,
      function(){
        theParentImage[0].style.backgroundPosition=self.icon_open;
      }
    );
  }
  else {
    $(theParentULs[0]).slideUp(
      self.effect_duration*1000,
      function(){
        theParentImage[0].style.backgroundPosition=self.icon_closed;
        self.navigation_scrollbar.scrollTop=0;
      }
    );
  }
};

// ************************************
// * open_item()                      *
// ************************************
function open_item(type,ID,page) {
  switch (type) {
    case 'article':
    case 'event':
    case 'news':
    case 'job':
    case 'podcast':
      popWin(
        base_url+type+'/'+ID,type+'_'+ID,'location=1,status=1,scrollbars=1,resizable=1',720,400,1
      );
    break;
    case 'page':
      popWin(
        base_url+page,type+'_'+ID,'location=1,status=1,scrollbars=1,resizable=1',720,400,1
      );
    break;
    default:
      alert("open_item() - unknown type '"+type+"'");
    break;
  }
}

// ************************************
// * Polling functions                *
// ************************************
function poll(mode,ID) {
  var a, a_count, elements, i, ids, max;
  var pw =
    "<img class='fl' src='"+base_url+"img/sysimg/progress_indicator.gif' width='16' height='16' alt='Please wait...'>"+
    "<div class='fl' style='color:#808080'><em>&nbsp;Loading... Please Wait</em></div><div class='clr_b'></div>";
  switch (mode){
    case "limit":
      a = geid_val('poll_choice_for_'+ID);
      a_count = (a==='' ? 0 : a.split(',').length);
      max = parseInt(geid_val('poll_max_votes_for_'+ID),10);
      elements = geid('poll_'+ID).getElementsByTagName('input');
      ids = [];
      for (i=0; i<elements.length; i++){
        if(elements[i].type==='checkbox'){
          geid('poll_choice_row_'+elements[i].value).className=(elements[i].checked ? 'selected ' : '');
          if (a_count>=max) {
            geid('poll_choice_row_'+elements[i].value).className+=(elements[i].checked ? '' : 'disabled ');
            elements[i].disabled=!elements[i].checked;
          }
          else {
            elements[i].disabled=false;
          }
        }
      }
    break;
    case "result":
      geid('poll_'+ID).innerHTML=pw;
      include(base_url+'?command=poll_result&targetID='+ID,'poll_'+ID);
      return false;
    case "show":
      geid('poll_'+ID).innerHTML=pw;
      include(base_url+'?command=poll_show&targetID='+ID,'poll_'+ID);
      return false;
    case "vote":
      a = geid_val('poll_choice_for_'+ID);
      a_count = (a==='' ? 0 : a.split(',').length);
      max = parseInt(geid_val('poll_max_votes_for_'+ID),10);
      if (!a_count) {
        alert('Please choose '+(max>1 ? 'up to '+max+' options' : 'an option'));
        return false;
      }
      if (a_count<max){
        if (!confirm('You may choose up to '+max+' options - continue with '+a_count+'?')) {
          return false;
        }
      }
      geid('poll_'+ID).innerHTML = pw;
      include(base_url+'?command=poll_vote&targetID='+ID+'&targetValue='+a,'poll_'+ID);
      return false;
  }
  return true;
}

function print_zone(id,title){
  var WindowObject = window.open('', "PrintWindow", "width=200,height=140,toolbars=no,scrollbars=auto,status=no,resizable=yes");
  var hd =  WindowObject.document;
  hd.write(
    "<html>\n"+
    "<head>\n"+
    "<title>"+title+"</title>\n"+
    "<style type='text/css'>\n"+
    "@media print {\n"+
    "  .np { display:none; }\n"+
    "  .clr_b { clear: both; }\n"+
    "}\n"+
    "@media screen {\n"+
    "  body { background: #e0e0ff; margin: 10px; }\n"+
    "  body * { display: none;}\n"+
    "  .np { display: block; height:100px; margin: 0; text-align: center }\n"+
    "  .np * { display: block; font-size: 18pt; }\n"+
    "}\n"+
    "</style>\n"+
    "<script type='text/javascript'>\n"+
    "if (window.print()){\n"+
    "  window.close();\n"+
    "}\n"+
    "</script>\n"+
    "</head>\n"+
    "<body>\n"+
    "<div class='np'>\n"+
    "<h1>Didn't work?<br />Try Control+P</h1>\n"+
    "<input type='button' value='Close' onclick='self.close();return false;' />\n"+
    "</div>\n"+
    "<h1>"+title+"</h1>\n"
  );
  var content = geid(id).innerHTML;
  hd.write(content);
  hd.write("</body>\n</html>");
  hd.close();
  WindowObject.focus();
}

// ************************************
// * Rating functions                 *
// ************************************
function rating_blocks_init(){
  function rating_blocks_set_fn(id,obj,value){
    var fn, obj2, obj3, i, n;
    obj.title='Click to award a rating of '+value;
    fn =
      function(e){
      obj2 = geid('rating_'+id);
      n=0;
      for (i=0; i<obj2.childNodes.length; i++){
        obj3 = obj2.childNodes[i];
        if (obj3.nodeType === 1) {
          obj3.style.backgroundPosition=(value>n++ ? '0px -26px' : '0px -39px');
        }
      }
    };
    obj.onmouseover=fn; // workaround since addEvent method fails in this context for Opera - don't know why.
    fn =
      function(e){
        obj2 = geid('rating_'+id);
        for (i=0; i<obj2.childNodes.length; i++){
          obj3 = obj2.childNodes[i];
          if (obj3.nodeType === 1) {
            obj3.style.backgroundPosition='0px -39px';
          }
        }
      };
    obj.onmouseout=fn;
    fn =
      function(){
        this.blur();rating_submit(id,value);return false;
      };
    obj.onclick=fn;
  }
  var i, j, k, l, obj, obj2;
  var n = 1;
  for (i=0; i<rating_blocks.length; i++) {
    obj = geid('rating_'+rating_blocks[i]);
    for (j=0; j<obj.childNodes.length; j++){
      for (k = 0, l = obj.childNodes[j].childNodes.length; k < l; k++) {
        obj2 = obj.childNodes[j].childNodes[k];
        if (obj2.nodeType === 1) {
          rating_blocks_set_fn(rating_blocks[i],obj2,n++);
        }
      }
    }
  }
}

function rating_submit(id,value) {
  var pw =
    "<img class='fl' src='"+base_url+"img/sysimg/progress_indicator.gif' width='16' height='16' alt='Please wait...'>"+
    "<div class='fl' style='color:#808080'><em>&nbsp;Recording... Please Wait</em></div><div class='clr_b'></div>";
  geid('rating_block_'+id).innerHTML=pw;
  include(base_url+'?command=rating_submit&targetID='+id+'&targetValue='+value,'rating_block_'+id);
}

function row_select_count(reportID){
  var csv = row_select_list(reportID);
  return (csv==='' ? 0 : csv.split(',').length);
}

function row_select_list(reportID){
  var element, elements, find, id, out;
  out = [];
  elements = geid('form').elements;
  for (var i=0; i<=elements.length; i++){
    element = elements[i];
    if (element && element.type==='checkbox' && element.id && element.checked){
      id = element.id;
      find = 'row_select_'+reportID+'_';
      if (id.substr(0,find.length)==find) {
        out.push(id.substr(find.length));
      }
    }
  }
  return out.join(',');
}

function setupSwatches(){
  $('.spectrum').spectrum({
    allowEmpty:true,
    showInitial: true,
    showInput: true,
    showPalette: true,
    preferredFormat: 'hex6',
    change: function(color){
//      alert('|'+color+'|');
      $('#'+this.id.substr(0,this.id.length-2)).val(color!==null ? color.toHexString().substr(1) : '');
    }
  })
}

// ************************************
// * Popup Layer functions            *
// ************************************
/**
 * POPUP WINDOW CODE v1.1
 * By Seth Banks (webmaster at subimage dot com)
 * http://www.subimage.com/
 *
 * Contributions by Eric Angel (tab index code) and Scott (hiding/showing selects for IE users)
 * Up to date code can be found at http://www.subimage.com/dhtml/subModal
 * This code is free for you to use anywhere, just keep this comment block.
 */

// Popup code
var gPopupMask = null;
var gPopupContainer = null;
var gPopFrame = null;
var gReturnFunc;
var gPopupIsShown = false;
var gFckEditorsLoading = 0;
var gHideSelects = false;
var gTabIndexes = [];
// Pre-defined list of tags we want to disable/enable tabbing into
var gTabbableTags = ["A","BUTTON","TEXTAREA","INPUT","IFRAME"];

// If using Mozilla or Firefox, use Tab-key trap.
if (!document.all) {
	document.onkeypress = keyDownHandler;
}

/**
 * Initializes popup code on load.
 */

/**
 * @argument width - int in pixels
 * @argument height - int in pixels
 * @argument returnFunc - function to call when returning true from the window.
 */
function showPopWin(title,body,width,height,returnFunc) {
  gPopupMask =		geid("popupMask");
  gPopupContainer =	geid("popupContainer");
  gPopFrame =		geid("popupFrame");
  gPopupIsShown =   true;
  disableTabIndexes();
  gPopupMask.style.display = "block";
  gPopupContainer.style.display = "block";
  centerPopWin(width, height);
  var titleBarHeight = parseInt(geid("popupTitleBar").offsetHeight, 10);
  if (width)  { gPopupContainer.style.width = width + "px"; }
  if (height) { gPopupContainer.style.height = (height+titleBarHeight) + "px"; }
  geid("popupTitle").innerHTML='<strong>'+system_family+'</strong>: '+title;
  geid("popupBody").innerHTML=body;
  gReturnFunc = returnFunc;
  if (isIE_lt7) {
    hideSelectBoxes();
  }
  applets_hide();
  $('#popupInner').draggable({ handle: '#popupTitleBar', opacity:0.9});
}

function setFocus(id) {
  if (!geid(id)){
    return;
  }
  var obj = geid(id);
  obj.focus(); // needs to be done twice for IE7 - don't know why
  obj.focus(); // needs to be done twice for IE7 - don't know why
}

function centerPopWin(width, height) {
  gPopupContainer = geid('popupContainer');
  if (gPopupIsShown === true) {
    if (width === null || isNaN(width)) {
      width = gPopupContainer.offsetWidth;
    }
    if (height === null || isNaN(height)) {
      height = gPopupContainer.offsetHeight;
    }
    var fullHeight = $(window).height();
    var fullWidth = $(window).width();
    var theBody = document.documentElement;
    var scTop = parseInt(theBody.scrollTop,10);
    var scLeft = parseInt(theBody.scrollLeft,10);
    gPopupMask.style.height = fullHeight + "px";
    gPopupMask.style.width = fullWidth + "px";
    gPopupMask.style.top = scTop + "px";
    gPopupMask.style.left = scLeft + "px";
    var titleBarHeight = parseInt(geid("popupTitleBar").offsetHeight, 10);
    gPopupContainer.style.top =   (scTop + ((fullHeight - (height+titleBarHeight)) / 2)) + "px";
    gPopupContainer.style.left =  (scLeft + ((fullWidth - width) / 2)) + "px";
  }
}
addEvent(window, "resize", centerPopWin);
addEvent(window, "scroll", centerPopWin);
window.onscroll = centerPopWin;

/**
 * @argument callReturnFunc - bool - determines if we call the return function specified
 * @argument returnVal - anything - return value
 */
function hidePopWin(callReturnFunc) {
  gPopupIsShown = false;
  restoreTabIndexes();
  if (gPopupMask === null) {
    return;
  }
  gPopupMask.style.display = "none";
  gPopupContainer.style.display = "none";
  geid("popupBody").innerHTML = '';
  if (callReturnFunc === true && gReturnFunc !== null) {
    gReturnFunc(window.frames.popupFrame.returnVal);
  }
  if (gHideSelects === true) {
    displaySelectBoxes();
  }
  applets_show();
}

// Tab key trap. iff popup is shown and key was [TAB], suppress it.
// @argument e - event - keyboard event that caused this function to be called.
function keyDownHandler(e) {
  if (gPopupIsShown && e.keyCode === 9) {
    return false;
  }
  return true;
}

// For IE.  Go through predefined tags and disable tabbing into them.
function disableTabIndexes() {
  var i, j, k, tagElements;
  if (document.all) {
    i = 0;
    for (j = 0; j < gTabbableTags.length; j++) {
      tagElements = document.getElementsByTagName(gTabbableTags[j]);
      for (k = 0 ; k < tagElements.length; k++) {
        gTabIndexes[i] = tagElements[k].tabIndex;
        tagElements[k].tabIndex="-1";
        i++;
      }
    }
  }
}

// For IE. Restore tab-indexes.
function restoreTabIndexes() {
  var i, j, k, tagElements;
  if (document.all) {
    i = 0;
    for (j = 0; j < gTabbableTags.length; j++) {
      tagElements = document.getElementsByTagName(gTabbableTags[j]);
      for (k = 0 ; k < tagElements.length; k++) {
        tagElements[k].tabIndex = gTabIndexes[i];
        tagElements[k].tabEnabled = true;
        i++;
      }
    }
  }
}


/**
* Hides all drop down form select boxes on the screen so they do not appear above the mask layer.
* IE has a problem with wanted select form tags to always be the topmost z-index or layer
*
* Thanks for the code Scott!
*/
function hideSelectBoxes() {
  var e, i;
  for(i = 0; i < document.forms.length; i++) {
    for(e = 0; e < document.forms[i].length; e++){
      if(document.forms[i].elements[e].tagName === "SELECT") {
        document.forms[i].elements[e].style.visibility="hidden";
      }
    }
  }
}

/**
* Makes all drop down form select boxes on the screen visible so they do not reappear after the dialog is closed.
* IE has a problem with wanted select form tags to always be the topmost z-index or layer
*/
function displaySelectBoxes() {
  var e, i;
  for(i = 0; i < document.forms.length; i++) {
    for(e = 0; e < document.forms[i].length; e++){
      if(document.forms[i].elements[e].tagName === "SELECT") {
        document.forms[i].elements[e].style.visibility="visible";
      }
    }
  }
}

function popup_hide_on_loaded() {
  if (gFckEditorsLoading===0) {
    hidePopWin(null);
  }
  else {
    setTimeout(function(){popup_hide_on_loaded();},500);
  }
}

// ************************************
// * (End of Popup Layer functions)   *
// ************************************
// These ones are mine...

// ************************************
// * Popup Dialog                     *
// ************************************
function popup_dialog(title,html,width,height,btn_ok_txt,btn_cancel_txt,btn_ok_js,focus,btn_cancel_js){
  var h,response;
  h =
    "<div id='popup_wait' style='display:none;'>"+
    "<img class='fl' src='"+base_url+"img/sysimg/icon_hourglass.gif' width='32' height='32' alt='Please wait...'>"+
    "<div class='fl' id='popup_wait_txt'></div>"+
    "<div class='clr_b'></div>"+
    "</div>"+
    "<div id='popup_content'>"+
    html+
    "</div>"+
    (btn_ok_txt || btn_cancel_txt ? "<div id='popup_buttons' style='padding-top:5px;margin:auto' class='txt_c'>" : "")+
    (typeof btn_ok_txt==='undefined' || btn_ok_txt==='' ?
        ""
      :
        "<input type='button' id='btn_ok' class='formButton' style='width:60px;' value='"+btn_ok_txt+"' "+
        "onclick=\""+
        (btn_ok_js!=='' ? btn_ok_js+";" : "")+
        "this.disabled=1;if(geid('btn_cancel')){geid('btn_cancel').disabled=1;};"+
        "hidePopWin(null);"+
        "\" />"
     )+
     (typeof btn_cancel_txt==='undefined' || btn_cancel_txt==="" ?
        ""
      : "<input type='button' id='btn_cancel' class='formButton' style='width:60px;' value='"+btn_cancel_txt+"' "+
        "onclick=\""+
        (typeof btn_cancel_js!=='undefined' && btn_cancel_js!=='' ? btn_cancel_js+";" : "")+
        "hidePopWin(null);\""+
        " />"
     )+
     (btn_ok_txt || btn_cancel_txt ? "</div>" : "")
     ;
  showPopWin(title,h,width,height,null);
  if (typeof focus!=='undefined' && focus) {
    setFocus(focus);
  }
}

function popup_wait(txt) {
  geid('popup_buttons').style.display='none';
  geid('popup_wait_txt').innerHTML=txt;
  geid('popup_content').style.display='none';
  geid('popup_wait').style.display='';
}

function popup_layer(title,mode,width,height){
  var h, url;
  h = "<div id='popup_form'><div style='padding:4px'>Loading...</div></div>";
  if (mode==='community_member_dashboard') {
    popup_dialog(title,h,width,height,'','');
  }
  else {
    popup_dialog(title,h,width,height,'','Close');
  }
  $.ajax({
    dataType: 'json',
    type: 'POST',
    url: base_url+'_popup_layer/'+mode+'/'+width+'/'+height
  })
  .done(function(transport){
    var layer, json;
    layer = $('#popup_form')[0];
    json = transport;
    layer.innerHTML=json.html;
    eval(json.js);
    if (json.css){ cssAddJsonStyle(eval(json.css));}
  });
}

function popup_layer_submit(url,args){
  var arg_arr, args_arr, post_vars, frm, field, i, value, vars;
  window.focus();
  frm = $('#form')[0];
  post_vars = [];
  for (i=0; i<frm.elements.length; i++){
    field = frm.elements[i].name;
    value = geid_val(field);
    if (value!=='') {
      switch(field) {
        case "goto":
        case "mode":
          // do nothing
        break;
        default:
          post_vars.push(field+'='+value);
        break;
      }
    }
  }
  vars = post_vars.join('&')+(typeof('args')!=='undefined' ? '&'+args : '');
  post_vars = {};
  args_arr = vars.split('&');
  for(i=0; i<args_arr.length;i++){
    arg_arr = args_arr[i].split('=');
    post_vars[arg_arr[0]] = arg_arr[1];
  }
  $.ajax({
    data: post_vars,
    dataType: 'json',
    type: 'POST',
    url: url
  })
  .done(function(transport){
    var layer, json;
    layer = $('#popup_form')[0];
    json = transport;
    layer.innerHTML=json.html;
    eval(json.js);
    if (json.css){ cssAddJsonStyle(eval(json.css));}
  });
}

function show_popup_please_wait(msg,w,h) {
  if (typeof msg==='undefined') { msg = "Loading...<br />Please wait"; }
  if (typeof w==='undefined')   { w = 120; }
  if (typeof h==='undefined')   { h = 90; }
  var html =
    "<div style='padding:4px'>" +
    "<img class='fl' src='"+base_url+"img/sysimg/icon_hourglass.gif' width='32' height='32' alt='Please wait...'>" +
    msg +
    "<br class='clr_b' /></div>";
  showPopWin("",html,w,h,null);
}

function selector_csv_add(div,value,hasWeight){
  var i, included, selector, val_arr;
  selector = geid('selector_csv_'+div);
  val_arr = geid_val(div).split(',');
  included = false;
  for (i=0; i<val_arr.length; i++){
    if (Trim(val_arr[i])===value){
      included = true;
    }
  }
  if (!included) {
    if((geid_val(div)+', '+value+'[1]').length<255){
      val_arr.push((hasWeight ? value+'[1]' : value));
      geid(div).value=val_arr.join(', ');
      selector_csv_delete(div,'',hasWeight); // Clear null value if present
    }
    else {
      alert('Sorry! This field cannot hold more than 255 characters in total.');
    }
  }
  selector_csv_show(div,hasWeight);
  selector.selectedIndex=0;
}

function selector_csv_delete(div,setting,hasWeight){
  var ctrl_item_param, i, input_arr, j, list_item, list_item_arr, list_item_param, list_item_value, out_val, selector;
  selector = geid('selector_csv_'+div);
  out_val =  [];
  input_arr = geid_val(div).split(',');
  for (i=0; i<input_arr.length; i++){
    if (Trim(input_arr[i].split('[')[0]) !== setting.split('[')[0]){
      for (j=0; j<selector.options.length; j++){
        list_item = Trim(input_arr[i]).toUpperCase();
        list_item_arr = list_item.split('[');
        list_item_param = list_item_arr[0];
        list_item_value = (typeof(list_item_arr[1])!=='undefined' ? list_item_arr[1].split(']')[0] : "");
        ctrl_item_param = selector.options[j].value.toUpperCase();
        if (list_item_param===ctrl_item_param){
          out_val[out_val.length] = selector.options[j].value+(list_item_value==="" ? "" : "["+list_item_value+"]");
        }
      }
    }
  }
  geid(div).value = out_val.join(', ');
  selector_csv_show(div,hasWeight);
}

function selector_csv_edit(div,setting,hasWeight){
  var h, j, param, setting_arr, value;
  if (hasWeight!==1){
    selector_csv_delete(div,setting,hasWeight);
    return;
  }
  setting_arr = setting.split('[');
  param =  setting_arr[0];
  value =  (typeof(setting_arr[1])!=='undefined' ? setting_arr[1].split(']')[0] : "");
  h =
    "<div style='padding:4px;'><p>Please give a weighting for <b>"+param+"</b><br />"+
    "<input type='text' class='formField' style='width:25px' id='selector_csv_edit_val' value='"+value+"' /></p>"+
    "<p>Or <a href=\"javascript:selector_csv_delete('"+div+"','"+param+"',1);hidePopWin(null);\"><b>click here</b></a> "+
    "to remove this entry.</p></div>";
  j =
    "var new_val = parseFloat(geid_val('selector_csv_edit_val'));if(isNaN(new_val)){new_val=0};"+
    "selector_csv_delete('"+div+"','"+param+"',1);"+
    "selector_csv_add('"+div+"','"+param+"'+(new_val==0 ? '' : '['+new_val+']'),1);"+
    "hidePopWin(null);return false;";
  popup_dialog("Set weighting",h,230,300,'OK','Cancel',j);
}

function selector_csv_show(div,hasWeight){
  var bgcolorClass, ctrl_item_param, i, input_arr, j, label, list_item,
    list_item_arr, list_item_param, list_item_value, out_txt,
    selector, tooltip, value;
  out_txt =  [];
  selector = geid('selector_csv_'+div);
  input_arr = geid_val(div).replace(/ ,/g,',').split(',');
  for (i=0; i<input_arr.length; i++){
    for (j=0; j<selector.options.length; j++){
      list_item = Trim(input_arr[i]).toUpperCase();
      list_item_arr = list_item.split("[");
      list_item_param = list_item_arr[0];
      list_item_value = (typeof(list_item_arr[1])!=='undefined' ? list_item_arr[1].split(']')[0] : "");
      ctrl_item_param = selector.options[j].value.toUpperCase();
      if (list_item_param===ctrl_item_param){
        bgcolorClass = selector.options[j].className;
        label =        selector.options[j].text;
        label =        Trim((label.substr(0,2)==='* ' ? label.substr(2) : label).split("(")[0]);
        label =	       label+(list_item_value==="" ? "" : "["+list_item_value+"]");
        tooltip =      (label.substr(0,2)==='* ' ? label.substr(2) : label);
        tooltip =      (tooltip.substr(tooltip.length-1)===')' ? tooltip.substr(0,tooltip.indexOf('(')) : tooltip);
        value =        selector.options[j].value+(list_item_value==="" ? "" : "["+list_item_value+"]");
        out_txt[out_txt.length] =
          "<a class=\""+bgcolorClass+"\" title=\""+
          (value ?
             "Click to"+
             (hasWeight ? " edit or" : "")+
             " remove '<b>"+tooltip+"</b>' from this list\\nor choose more options from the dropdown list"
           : "Choose entries from the dropdown list"
           )+
          "\" href=\"javascript:selector_csv_edit('"+div+"','"+value+"',"+hasWeight+")\">"+
          label+
          "</a>";
      }
    }
  }
  geid('selector_csv_div_'+div).innerHTML=out_txt.join(', ');
  ToolTips.out();
  ToolTips.attachBehavior();
}

function status_message_hide(id){
  if (geid(id)){
    window.focus();
    geid(id).style.display = 'none';
    geid(id).innerHTML = '';
  }
}

function status_message_show(id,message,severity,noclose){
  var border, bg, color, div, highlight;
  if (typeof noclose ==='undefined') {
    noclose = false;
  }
  div = geid(id);
  if (div===false) {
    return false;
  }
  switch(severity){
    case 0:
      border = '#008000'; bg = '#d0ffd0'; color = '#004000'; highlight = '#80ff80';
    break;
    case 1:
      border = '#808000'; bg = '#FFFFD0'; color = '#404000'; highlight = '#FFFF80';
    break;
    case 2:
      border = '#ff0000'; bg = '#ffd0d0'; color = '#800000'; highlight = '#ff8080';
    break;
  }
  div.className =          'form_status';
  div.style.background =   bg;
  div.style.borderColor =  border;
  div.style.color =        color;
  div.style.display =      'none';
  div.style.padding =      '0.5em';
  div.innerHTML =
    "<div class='fl'>"+message+"</div>\n" +
    (noclose ?
      ""
    :
      "  <input type='button' value='OK' class='formButton fr' style='width:50px;'" +
      " onclick=\"window.focus();this.parentNode.style.display='none';return false\" />"
    ) +
    "  <div class='clr_b'></div>";
  div.style.display='';
  $('#'+id).effect('highlight',{'color':highlight},3000);
  return true;
}


function cssAddJsonStyle(cssObj) {
  var i;
  for (i=0; i<cssObj.length; i++){
    if (cssObj[i]){
      $(cssObj[i][0]).each(
        function(idx,el){
          $(cssObj[i][1]).each(
            function(idx2,el2){
              $(el).css(el2);
            }
          );
        }
      );
    }
  }
}

function cssAttributeGet(selectorText,attribute) {
  var styleSheet, rules, i, ii;
  selectorText=selectorText.toLowerCase();
  if (!document.styleSheets) {
    return false;
  }
  for (i=0; i<document.styleSheets.length; i++) {
    try{
      styleSheet=document.styleSheets[i];
      rules = (styleSheet.cssRules ? styleSheet.cssRules : styleSheet.rules);
      for (ii=0; ii<rules.length; ii++) {
        if (
          rules[ii] && rules[ii].selectorText &&
          rules[ii].selectorText.toLowerCase()===selectorText &&
          rules[ii].style[attribute]
        ){
          return (rules[ii].style[attribute]);
        }
      }
    }
    catch(e){
    };
  }
  return false;
}

function cssAttributeSet(selectorText,attribute,value) {
  var styleSheet, rules, i, ii;
  selectorText=selectorText.toLowerCase();
  if (!document.styleSheets) {
    return false;
  }
  for (i=0; i<document.styleSheets.length; i++) {
    try{
      styleSheet=document.styleSheets[i];
      rules = (styleSheet.cssRules ? styleSheet.cssRules : styleSheet.rules);
      for (ii=0; ii<rules.length; ii++) {
        if (
          rules[ii] && rules[ii].selectorText &&
          rules[ii].selectorText.toLowerCase()===selectorText
        ){
          rules[ii].style[attribute]=value;
          return true;
        }
      }
    }
    catch(e){
    };
  }
  return false;
}

function toggleTextSize() {
  var cur_size = cssAttributeGet('.zoom_text','fontSize');
  var expdate = new Date();
  expdate.setTime(expdate.getTime() + (1000*3600*24*365));
  document.cookie = 'textsize=' + (cur_size==='80%' ? 'big' : 'small') + '; expires=' + expdate.toGMTString();
  setDisplay('text_sizer_reduce',cur_size==='80%');
  setDisplay('text_sizer_enlarge',cur_size==='120%');
//  cssAttributeSet('.zoom_help','fontSize',(cur_size==='80%' ? '10pt' : '7pt'));
  document.body.className = document.body.className.replace((cur_size==='80%' ? 'zoom_small' : 'zoom_big'),'');
  document.body.className = (document.body.className ? ' ' : '') + (cur_size==='80%' ? 'zoom_big' : 'zoom_small');
  var font_scale = (cur_size==='80%' ? '120' : '80');
  if (!cssAttributeSet('.zoom_text','fontSize',font_scale+'%')){
    window.self.location.reload();
  }
}

function toggle_attachment_delete_flag(field){
  var state = geid(field+'_mark_delete').checked;
  geid('div_'+field+'_mark_delete_0').style.display = (state===true ? 'none' : '');
  geid('div_'+field+'_mark_delete_1').style.display = (state===true ? '' : 'none');
}


function initialise_constraints(){
  if (isIE && (isIE_lt8 || document.documentMode<8)){
    $('.constrain').each(function(obj){
      obj.outerHTML =
        "<table border='0' cellpadding='0' cellspacing='0'><tr><td>"+
        obj.outerHTML+
        "</td></tr></table>";
    });
  }
}

// ************************************
// * Static Tooltips                  *
// ************************************
/**
 * General Horde UI effects javascript.
 * $Horde: horde/js/horde.js,v 1.14.2.5 2006/05/25 18:07:26 slusarz Exp $
 * Changes by Martin Francis in 1.0.36 (2007-07-24) to initialise variables with var keyword
 * See http://www.fsf.org/copyleft/lgpl.html for LGPL licencing
 */

function initialise_tooltips() {
  var i, j, k, links;
  ToolTips.attachBehavior = function(){
    links = document.getElementsByTagName('a');
    for (i = 0; i < links.length; i++) {
      if (links[i].title && links[i].className=='info'){
        links[i].setAttribute('nicetitle', links[i].title);
        links[i].removeAttribute('title');
        addEvent(links[i], 'mouseover', ToolTips.over);
        addEvent(links[i], 'mouseout', ToolTips.out);
        addEvent(links[i], 'focus', ToolTips.over);
        addEvent(links[i], 'blur', ToolTips.out);
      }
    }
  };
  ToolTips.over = function(e){
    if (ToolTips.TIMEOUT) {
      window.clearTimeout(ToolTips.TIMEOUT);
    }
    if (window.event && window.event.srcElement) {
      ToolTips.LINK = window.event.srcElement;
    }
    else if (e && e.target) {
      ToolTips.LINK = e.target;
    }
    ToolTips.TIMEOUT = window.setTimeout(function(){ToolTips.show();}, 300);
  };
  ToolTips.out = function() {
    if (typeof ToolTips === 'undefined') {
      return;
    }
    if (ToolTips.TIMEOUT) {
      window.clearTimeout(ToolTips.TIMEOUT);
    }
    if (ToolTips.CURRENT) {
      document.getElementsByTagName('body')[0].removeChild(ToolTips.CURRENT);
      ToolTips.CURRENT = null;
      var iframe = geid('iframe_tt');
      if (iframe !== null) {
        iframe.style.display = 'none';
      }
    }
  };
  ToolTips.show = function() {
    var link, nicetitle, d, i, STD_WIDTH, MAX_WIDTH, nicetitle_length, lines, w;
    var h_pixels, mpos, mx, my, left;
    if (typeof ToolTips === 'undefined' || !ToolTips.LINK) {
      return;
    }
    if (ToolTips.CURRENT) {
      ToolTips.out();
    }
    link = ToolTips.LINK;
    while (!link.getAttribute('nicetitle') && link.nodeName.toLowerCase() !== 'body') {
      link = link.parentNode;
    }
    nicetitle = link.getAttribute('nicetitle');
    if (!nicetitle) {
      return;
    }
    nicetitle = nicetitle.replace(/ /g,"&nbsp;").replace(/\[b\]/g,"<b>").replace(/\[\/b\]/g,"</b>").replace(/\\n/g,"<br />");
    d = document.createElement('div');
    d.className = 'nicetitle';
    d.innerHTML = nicetitle;
    STD_WIDTH = 100;
    MAX_WIDTH = 600;
    if (window.innerWidth) {
      MAX_WIDTH = Math.min(MAX_WIDTH, window.innerWidth - 20);
    }
    if (document.body && document.body.scrollWidth) {
      MAX_WIDTH = Math.min(MAX_WIDTH, document.body.scrollWidth - 20);
    }
    nicetitle_length = 0;
    lines =
      nicetitle.replace(/&nbsp;/g," ").
      replace(/<b>/g,"").
      replace(/<\/b>/g,"").
      replace(/<br ?\/>/g,"\n").
      split("\n");
    var ls_arr =
      [
        [3,"ijl"],
        [4,"frt!,."],
        [5,"IJac()-[]:;\"/|\\"],
        [6,"EFLPnsvyz`_{}?"],
        [7,"ABDKNRSTUVXYZbdeghkopquwx1234567890$&* "],
        [8,"CGH~#^+=<>"],
        [9,"MOQ"],
        [10,"W@"],
        [11,"m%"]
      ];
    nicetitle_length = 0;
    for (i=0;i<lines.length;i++) {
      var this_line = Trim(lines[i]);
      var this_length = 0;
      for (j=0; j<this_line.length; j++){
        for (k=0; k<ls_arr.length; k++){
          if(ls_arr[k][1].indexOf(this_line[j])!==-1){
            this_length+=ls_arr[k][0];
          }
        }
      }
      nicetitle_length = Math.max(nicetitle_length, this_length);
    }
    h_pixels = (nicetitle_length+10)*0.9;
    if (h_pixels > STD_WIDTH) {
      w = h_pixels;
    }
    else if (STD_WIDTH > h_pixels) {
      w = h_pixels;
    }
    else {
      w = STD_WIDTH;
    }
    mpos = obj_getCoords(link);
    mx = mpos.left;
    my = mpos.top;
    left = mx + 20;
    if (window.innerWidth && ((left + w) > window.innerWidth)) {
      left = window.innerWidth - w - 40;
    }
    if (document.body && document.body.scrollWidth && ((left + w) > document.body.scrollWidth)) {
      left = document.body.scrollWidth - w - 25;
    }
    d.id = 'toolTip';
    d.style.left = Math.max(left, 5) + 'px';
    d.style.width = Math.min(w, MAX_WIDTH) + 'px';
    d.style.top = (my + 25) + 'px';
    d.style.display = "block";
    d.style.zIndex = 100;
    try {
      document.getElementsByTagName('body')[0].appendChild(d);
      ToolTips.CURRENT = d;
    }
    catch (e) {
    }
  };
}

var EventCache = function(){
  var listEvents = [];
  return {
    listEvents: listEvents,
    add: function(node, sEventName, fHandler, bCapture) {
      listEvents.push(arguments);
    },
    flush: function() {
      var i, item;
      for (i = listEvents.length - 1; i >= 0; i = i - 1) {
        item = listEvents[i];
        if (item[0].removeEventListener) {
          item[0].removeEventListener(item[1], item[2], item[3]);
        }
        /* From this point on we need the event names to be
         * prefixed with 'on'. */
        if (item[1].substring(0, 2)!=='on') {
          item[1] = 'on' + item[1];
        }
        if (item[0].detachEvent) {
          item[0].detachEvent(item[1], item[2]);
        }
        item[0][item[1]] = null;
      }
    }
  };
}();

/**
 * end of General Horde UI effects javascript.
 * See http://www.fsf.org/copyleft/lgpl.html for LGPL licencing
 */

// ************************************
// * Navsuite code                    *
// ************************************
// Fired on navbar button mouseover
function img_state(img,state) {
  var obj =	geid('btn_'+img);
  var h =	obj.height;
  var pos_arr =	obj.style.backgroundPosition.split(' ');
  var new_pos = "";
  switch (state) {
    case 'a':
      new_pos = 0;
    break;
    case 'd':
      new_pos = -1*h;
    break;
    case 'n':
      new_pos = -2*h;
    break;
    case 'o':
      new_pos = -3*h;
    break;
  }
  obj.style.backgroundPosition = pos_arr[0]+' '+new_pos+'px';
  return true;
}

function img_state_v(img,state) {
  var obj =	geid(img);
  var pos_arr =	obj.style.backgroundPosition.split(' ');
  var new_pos = "";
  switch (state) {
    case 'a':
      new_pos = 0;
    break;
    case 'd':
      new_pos = -100;
    break;
    case 'n':
      new_pos = -200;
    break;
    case 'o':
      new_pos = -300;
    break;
  }
  obj.style.backgroundPosition = new_pos+'% '+pos_arr[1];
  return true;
}

// ************************************
// * SDMenu Functions                 *
// ************************************
function SDMenu(id, speed, oneOnly) {
  if (!document.getElementsByTagName){
    return false;
  }
  this.menu =           geid(id);
  this.submenus =       this.menu.getElementsByTagName("li");
  this.speed =          speed;
  this.oneSmOnly =      oneOnly;
  return true;
}

SDMenu.prototype.init = function() {
  var i, j, links, location, mainInstance, match, regex, sdmenu_current, sdmenu_links, states;
  mainInstance = this;
  for (i=0; i<this.submenus.length; i++){
    if (typeof this.submenus[i].getElementsByTagName("span")[0]!=='undefined'){
      this.submenus[i].getElementsByTagName("span")[0].onclick = function() {
        mainInstance.toggleMenu(this.parentNode);
      };
    }
  }
  location = window.location.protocol + "//" + window.location.host + "/" + geid_val('goto');
  links = this.menu.getElementsByTagName("a");
  for (i = 0; i < links.length; i++) {
    if (location===links[i].href) {
      links[i].className = "current";
      break;
    }
    if (location===links[i].href+'home') {
      links[i].className = "current";
      break;
    }
  }
  for (i=0; i < this.submenus.length; i++) {
    sdmenu_current = false;
    sdmenu_links = this.submenus[i].getElementsByTagName('a');
    for (j = 0; j < sdmenu_links.length; j++){
      if (location === sdmenu_links[j].href) {
        sdmenu_current = true;
      }
      if (location === sdmenu_links[j].href+'home') {
        sdmenu_current = true;
      }
    }
    if (sdmenu_current) {
      this.expandMenu(this.submenus[i]);
    }
    else {
      this.collapseMenu(this.submenus[i]);
    }
  }
};

SDMenu.prototype.toggleMenu = function(submenu) {
  if (submenu.className === "collapsed"){
    this.expandMenu(submenu);
  }
  else {
    this.collapseMenu(submenu);
  }
};

SDMenu.prototype.expandMenu = function(submenu) {
  var curHeight, fullHeight, i, intId, links, newHeight, mainInstance, moveBy;
  if (typeof submenu.getElementsByTagName("span")[0]==='undefined'){
    return;
  }
  fullHeight = submenu.getElementsByTagName("span")[0].offsetHeight;
  links = submenu.getElementsByTagName("a");
  for (i=0; i<links.length; i++){
    fullHeight += links[i].offsetHeight;
  }
  moveBy = Math.round(this.speed * links.length);
  mainInstance = this;
  intId = setInterval(function() {
    curHeight = submenu.offsetHeight;
    newHeight = curHeight + moveBy;
    if (newHeight < fullHeight){
      submenu.style.height = newHeight + "px";
    }
    else {
      clearInterval(intId);
      submenu.style.height = "";
      submenu.className = "";
    }
  }, 30);
  this.collapseOthers(submenu);
};

SDMenu.prototype.collapseMenu = function(submenu) {
  var curHeight, intId, mainInstance, minHeight, moveBy, newHeight;
  if (typeof(submenu.getElementsByTagName("span")[0])==='undefined'){
    return;
  }
  minHeight = submenu.getElementsByTagName("span")[0].offsetHeight;
  moveBy = Math.round(this.speed * submenu.getElementsByTagName("a").length);
  mainInstance = this;
  intId = setInterval(function() {
    curHeight = submenu.offsetHeight;
    newHeight = curHeight - moveBy;
    if (newHeight > minHeight){
      submenu.style.height = newHeight + "px";
    }
    else {
      clearInterval(intId);
      submenu.style.height = "";
      submenu.className = "collapsed";
    }
  }, 30);
};

SDMenu.prototype.collapseOthers = function(submenu) {
  var i;
  if (this.oneSmOnly) {
    for (i = 0; i < this.submenus.length; i++){
      if (this.submenus[i] !== submenu && this.submenus[i].className !== "collapsed"){
        this.collapseMenu(this.submenus[i]);
      }
    }
  }
};

SDMenu.prototype.expandAll = function() {
  var i, oldOneSmOnly;
  oldOneSmOnly = this.oneSmOnly;
  this.oneSmOnly = false;
  for (i = 0; i < this.submenus.length; i++){
    if (this.submenus[i].className === "collapsed"){
      this.expandMenu(this.submenus[i]);
    }
  }
  this.oneSmOnly = oldOneSmOnly;
};

SDMenu.prototype.collapseAll = function() {
  var i;
  for (i = 0; i < this.submenus.length; i++){
    if (this.submenus[i].className !== "collapsed"){
      this.collapseMenu(this.submenus[i]);
    }
  }
};

SDMenu.prototype.expandByPage = function(page) {
  var sdmenu_current, sdmenu_links, i, j, location;
  location = window.location.protocol + '//' + window.location.host + '/' +page;
  for (i=0; i < this.submenus.length; i++) {
    sdmenu_current = false;
    sdmenu_links = this.submenus[i].getElementsByTagName('a');
    for (j = 0; j < sdmenu_links.length; j++){
      if (location === sdmenu_links[j].href) {
        sdmenu_current = true;
      }
    }
    if (sdmenu_current) {
      this.expandMenu(this.submenus[i]);
    }
    else {
      this.collapseMenu(this.submenus[i]);
    }
  }
};

function ajax_div_loading(obj,msg) {
  var c = obj_getCoords(obj);
  var div1 = obj.appendChild(document.createElement("div"));
  var div2 = obj.appendChild(document.createElement("div"));
  var ds1 = div1.style;
  var ds2 = div2.style;
  ds1.backgroundColor = "#aaaaaa";
  ds1.zIndex = 998;
  ds1.opacity = "0.6";
  ds1.filter = "alpha(opacity=60)";
  ds1.top = c.top;
  ds1.left = c.left;
  ds1.height = obj.offsetHeight;
  ds1.width = parseFloat(obj.offsetWidth)+20+"px";
  ds1.position = "absolute";
  ds2.backgroundColor = "#f0f0ff";
  ds2.color = "#404060";
  ds2.zIndex = 999;
  ds2.opacity = "0.95";
  ds2.filter = "alpha(opacity=95)";
  ds2.top = parseFloat(c.top)+10+"px";
  ds2.left = parseFloat(c.left)+10+"px";
  ds2.lineHeight = "40px";
  ds2.width = obj.offsetWidth;
  ds2.position = "absolute";
  ds2.textAlign = "center";
  ds2.fontSize = "14pt";
  ds2.border = "solid 1px #8080a0";
  div2.innerHTML = msg;
}

function ajax_keytest(e) {
  var i, keys_arr;
  if(!e) {
    e=window.event;
  }
  keys_arr = [9,37,38,39,40];
  for (i=0; i<keys_arr.length; i++) {
    if (e.keyCode===keys_arr[i]) {
      return false;
    }
  }
  return true;
}

function ajax_prepare_params(xParams){
  var i, key, params, param, params_arr, temp, val;
  if (typeof xParams==='string'){
    params_arr = xParams.split('&');
    params = {};
    for(i=0; i<params_arr.length; i++){
      param = params_arr[i].split('=');
      key = param.shift();
      val = param.join('=');
//      alert(param[0]);
      if (typeof params[key]!=='undefined'){
        temp = params[key];
        if (typeof temp==='string'){
          params[key] = [];
          params[key].push(temp);
        }
        params[key].push(val);
      }
      else {
        params[key] = val;
      }
    }
    xParams = params;
  }
  return xParams;
}

function ajax_post(xUrl,xId,xParams,xFn) {
  xParams = ajax_prepare_params(xParams);
  $.ajax({
    data: xParams,
    type: 'POST',
    url:  xUrl
  })
  .done(function(transport){
    if (geid(xId)) {
      geid(xId).innerHTML = transport;
    }
    if (typeof xFn==='function') {
      xFn(transport);
    }
  });
}

function ajax_post_streamed(xUrl,xId,xParams,xFn) {
  xParams = ajax_prepare_params(xParams);
  $.ajax({
    data: xParams,
    dataType: 'json',
    type: 'POST',
    url:  xUrl
  })
  .done(function(transport){
    if (geid(xId)) {
      var json = transport;
      geid(xId).innerHTML=json.html;
      eval(json.js);
      if (json.css){ cssAddJsonStyle(eval(json.css)) ;}
    }
    if (typeof xFn==='function') {
      xFn(transport);
    }
  });
}

function ajax_report(reportID,report_name,toolbar,ajax_popup_url,resource_url) {
  var pw, xFn;
  if (typeof resource_url==='undefined'){
    resource_url = base_url;
  }
  pw =
    "<img src='"+base_url+"img/sysimg/progress_indicator.gif' width='16' height='16' alt='Please wait...'>"+
    "&nbsp; <strong>"+system_family+" loading...</strong>";
  ajax_div_loading(geid('report_'+reportID),pw);
  xFn =
    function() {
      externalLinks();
      if (window.CM_load) { CM_load(); }
      ToolTips.out();ToolTips.attachBehavior();
      report_filter_setup();
    };
  ajax_post(
    resource_url+'?command=report',
    'report_'+reportID,
    'report_name='+report_name+
    (typeof ajax_popup_url!=='undefined' ? '&ajax_popup_url='+ajax_popup_url : '')+
    '&filterField='+geid_val('filterField')+
    '&filterExact='+geid_val('filterExact')+
    '&filterValue='+geid_val('filterValue')+
    '&limit='+geid_val('limit')+
    '&offset='+geid_val('offset')+
    '&selectID='+geid_val('selectID')+
    '&sortBy='+geid_val('sortBy')+
    '&submode='+geid_val('submode')+
    '&toolbar='+toolbar+
    '&targetID='+geid_val('targetID')+
    '&targetReportID='+geid_val('targetReportID')+
    '&DD='+geid_val('DD')+
    '&MM='+geid_val('MM')+
    '&YYYY='+geid_val('YYYY'),
     xFn
  );
}

function column_over(obj_cell,int_state) {
  switch (int_state) {
    case 'n': obj_cell.className = 'grid_head_n'; break;
    case 'o': obj_cell.className = 'grid_head_o'; break;
    case 'd': obj_cell.className = 'grid_head_d'; break;
  }
  return true;
}

function combo_selector_set(field_name,width){
  var val_a, val_s, obj_field, obj_field_alt, obj_field_sel, field_alt_span;
  obj_field =       geid(field_name);
  obj_field_alt =   geid(field_name+'_alt');
  obj_field_sel =   geid(field_name+'_selector');
  field_alt_span =  field_name+'_alt_span';
  val_s =           geid_val(obj_field_sel);
  val_a =           geid_val(obj_field_alt);
  if(val_a){
    for(var i=0; i<obj_field_sel.options.length; i++){
      if(obj_field_sel.options[i].value.toUpperCase()==val_a.toUpperCase()){
        geid(field_name+'_selector').selectedIndex = i;
        geid_set(field_name+'_alt','');
        geid_set(field_name,val_s);
        return combo_selector_set(field_name,width);
        break;
      }
    }
  }

  if (val_s==='--') {
    setDisplay(field_alt_span,1);
    if (width) {
      obj_field_sel.style.width=(parseInt(width,10)/2)+'px';
    }
    geid_set(field_name,val_a);
  }
  else {
    setDisplay(field_alt_span,0);
    if (obj_field_sel && obj_field_sel.style && obj_field_sel.style.width && width) {
      obj_field_sel.style.width=(parseInt(width,10)+4)+'px';
    }
    geid_set(field_name,val_s);
    geid_set(field_name+'_alt','');
  }
  if (val_s==='') {
    geid_set(field_name+'_selector','');
  }
  return geid_val(field_name);
}

function donate(key) {
  geid('command').value='donate';
  geid('form').target='_blank';
  geid('targetValue').value=key;
  geid('form').submit();
  geid('command').value='';
  geid('targetValue').value='';
  geid('form').target='';
}

function dropdown_range_field(id,min,max,onchange){
  var field, html, i, value;
  field =   geid(id);
  if (!field){
    return;
  }
  value =   geid_val(id);
  html =
     "<select class='formField' name='" + id + "' id='" + id + "'" +
     (typeof onchange!=='undefined' ? " onchange=\""+onchange+"\"" : "") +
     ">\n";
  for (var i=min; i<=max; i++){
    html+=
      "<option"+
      (value==i ? " selected='selected'" : '')+
      ">"+i+"</option>\n";
  }
  html+= "</select>\n";
  field.outerHTML = html;
}

function get_taxes_applied(BCountryID,BSpID,tax_arr) {
  var i, invert, n, place, result, tax, taxes;
  result = {};
  taxes = Object.keys(tax_arr);
  for(n=0; n < taxes.length; n++){
    tax = taxes[n];
    result[tax] = 0;
    for (i=0; i<tax_arr[tax][2].length; i++){
      place =  tax_arr[tax][2][i];
      invert = (place.substr(0,1)==='!');
      if (invert){
        place = place.substr(1);
        if (
          place !== BCountryID+'.'+BSpID &&
          place !== BCountryID+'.*' &&
          place !== '*.'+BSpID &&
          place !== '*.*'
        ){
          result[tax] = tax_arr[tax][0];
        }
      }
      else if (
        place === BCountryID+'.'+BSpID ||
        place === BCountryID+'.*' ||
        place === '*.'+BSpID ||
        place === '*.*'
      ){
        result[tax] = tax_arr[tax][0];
      }
    }
  }
  return result;
}

function get_valid_name(name) {
  if (isNaN(name.substr(0,1))) {
    return name;
  }
  return valid_prefix+name;
}

function goto_page(what) {
  geid('goto').value=what;
  geid('mode').value='';
  geid('form').submit();
}

function img_over(obj,baseimg,state) {
  var src = "";
  switch (baseimg) {
    case "link":
      src = base_url+'img/?mode=sysimg&img=txt_link_'+(state ? 'o' : 'n')+'.gif';
    break;
    case "map":
      src = base_url+'img/?mode=sysimg&img=txt_map_'+(state ? 'o' : 'n')+'.gif';
    break;
    case "read_more":
      src = base_url+'img/?mode=sysimg&img=txt_read_more_'+(state ? 'o' : 'n')+'.gif';
    break;
    default:
      src = base_url+'img/?mode=sysimg&img='+baseimg+'_'+(state ? 'o' : 'n')+'.gif';
    break;
  }
  obj.src = src;
  return true;
}

function include(xUrl,xId,xFn) {
  // http://jmaguire.com/downloads/source_code/javascript/client_side_include/
  // Used by Daily Bible Verse
  var xmlhttp = false;
  if (isIE_lt7){
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch (E) {
        xmlhttp = false;
      }
    }
  }
  if (!xmlhttp && typeof XMLHttpRequest!=='undefined') {
    xmlhttp = new XMLHttpRequest();
  }
  if (typeof(ajax_controls[xId])!=='undefined') {
    xmlhttp.abort();
    delete ajax_controls[xId];
  }
  xmlhttp.open("GET", xUrl,true);
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState===4) {
      delete ajax_controls[xId];
      if (geid(xId)) {
        geid(xId).innerHTML = xmlhttp.responseText;
      }
      if (xFn) {
        xFn();
      }
    }
  };
  ajax_controls[xId]=xmlhttp;
  xmlhttp.send(null);
}

function makeReadOnly(id) {
  geid(id).style.backgroundColor='#f0f0f0';
  geid(id).style.color='#404040';
  geid(id).attachEvent(
    "onfocus",
    function(){
      geid(id).blur();
    }
  );
}


// ************************************
// * nav_mouse()                      *
// ************************************
// simplifies nav rollovers for trees:
function nav_mouse(div,state,parentDiv){
  switch (state) {
    case "o":
      img_state(div,'o');
      if (parent!==null) {
        img_state(parentDiv,'o');
      }
    break;
    case "n":
      img_state(div,'n');
      if (parent!==null) {
        img_state(parentDiv,'n');
      }
    break;
  }
  return true;
}

function obj_getCoords(obj) {
  var xp, yp, op;
  xp = obj.offsetLeft;	// Element's offset x in pixels
  yp = obj.offsetTop;	// Element's offset y in pixels
  // Now loop through all parent containers, adding offsets as we do so
  while (obj.offsetParent) {
    op = obj.offsetParent;	// Get container parent
    xp = xp + op.offsetLeft;	// Add this element's offset x in pixels
    yp = yp + op.offsetTop;		// Add this element's offset y in pixels
    obj = obj.offsetParent;	// Update current container
  }
  return {'left': xp, 'top': yp};
}

function pad(text,spaces) {
  if (text.length>=spaces) {
    return text;
  }
  var padstr = '                 ';
  return text+padstr.substr(0,spaces-text.length);
}

function get_flag_sprite_x(flag){
  var flags =
    '?,ad,ae,af,ag,ai,al,am,an,ao,ar,as,at,au,aw,ax,az,ba,bb,bd,be,bf,bg,bh,bi,bj,bm,'+
    'bn,bo,br,bs,bt,bv,bw,by,bz,ca,catalonia,cc,cd,cf,cg,ch,ci,ck,cl,cm,cn,co,cr,cs,'+
    'cu,cv,cx,cy,cz,de,dj,dk,dm,do,dz,ec,ee,eg,eh,england,er,es,et,europeanunion,'+
    'fam,fi,fj,fk,fm,fo,fr,ga,gb,gd,ge,gf,gh,gi,gl,gm,gn,gp,gq,gr,gs,gt,gu,gw,gy,'+
    'hk,hm,hn,hr,ht,hu,id,ie,il,in,io,iq,ir,is,it,jm,jo,jp,ke,kg,kh,ki,km,kn,kp,kr,'+
    'kw,ky,kz,la,lb,lc,li,lk,lr,ls,lt,lu,lv,ly,ma,mc,md,me,mg,mh,mk,ml,mm,mn,mo,mp,'+
    'mq,mr,ms,mt,mu,mv,mw,mx,my,mz,na,nc,ne,nf,ng,ni,nl,no,np,nr,nu,nz,om,pa,pe,pf,'+
    'pg,ph,pk,pl,pm,pn,pr,ps,pt,pw,py,qa,re,ro,rs,ru,rw,sa,sb,sc,scotland,sd,se,sg,'+
    'sh,si,sj,sk,sl,sm,sn,so,sr,st,sv,sy,sz,tc,td,tf,tg,th,tj,tk,tl,tm,tn,to,tr,tt,'+
    'tv,tw,tz,ua,ug,um,us,uy,uz,va,vc,ve,vg,vi,vn,vu,wales,wf,ws,ye,yt,za,zm,zw';
  var flags_arr = flags.split(',');
  for(var i=0; i<flags_arr.length; i++){
    if (flag==flags_arr[i]){
      return i*-11;
    }
  }
  return 0;
}

function language_chooser(languages,lbl_title,lbl_ok,lbl_cancel){
  var entry_arr, i, js_ok, lang_arr, out;
  lang_arr = languages.split(',');
  out = "<div id='popup_form' style='padding:4px;'>";
  for(i=0; i<lang_arr.length; i++){
    entry_arr = lang_arr[i].split('|');
    out+=
      "<input type='radio' name='language' id='language_"+entry_arr[0]+"'"+
      (currentLanguage==entry_arr[0] ? " checked='checked'" : '')+
      " value='"+entry_arr[0]+"'"+
      "/>"+
      "<label for='language_"+entry_arr[0]+"'>"+
      "<img src='"+base_url+"img/spacer' width='16' height='11'"+
      " alt=\""+entry_arr[2]+"\""+
      " style=\"background: url("+base_url+"img/sysimg/flags.png)"+
      "0px "+get_flag_sprite_x(entry_arr[1])+"px;border: 0;"+
      "\" /> "+
      entry_arr[2]+
      "</label><br />";
  }
  out+= "</div>";
  js_ok =
    "geid_set('command','set_language');"+
    "geid_set('targetValue',geid_val('language'));"+
    "geid('form').submit();";
  popup_dialog(lbl_title,out,240,100,lbl_ok,lbl_cancel,js_ok);
  return false;
}

function language_set(lang){
  alert('Will change to '+lang);
}

function lead_zero(text,digits) {
  if (text.length>=digits) {
    return text;
  }
  var leadstr = '0000000000000000';
  return leadstr.substr(0,digits-text.length)+text;
}

// ************************************
// * popWin()                         *
// ************************************
function popWin(url,winName,features,width,height,centre) {
  var availx, availy, posx, posy, theWin;
  if (centre === "centre") {
    availx = screen.availWidth;
    availy = screen.availHeight;
    posx = (availx - width)/2;
    posy = (availy - height)/2;
    theWin =
      window.open(
        url,
        winName,
        features+',width='+width+',height='+height+',left='+posx+',top='+posy
      );
  }
  else {
    theWin =
      window.open(
        url,
        winName,
        features+',width='+width+',height='+height+',left=25,top=25'
      );
  }
  if (!theWin){
    alert(
      'ERROR:\n\n'+
      system_family+' tried to open a popup window\n'+
      'but was prevented from doing so.\n\n'+
      'Please disable any popup blockers you may\n'+
      'have enabled for this site.'
    );
    return false;
  }
  theWin.focus();
  return theWin;
}

function popWin_post(url,width,height){
  var targetReportID =	geid_val('targetReportID');
  var targetID =	geid_val('targetID');
  var selectID =	geid_val('selectID');
  var filterField =	geid_val('filterField');
  var filterExact =	geid_val('filterExact');
  var filterValue =	geid_val('filterValue');
  var window_name =	('pop_'+url).replace(/[\?\&\= :,\/\-\.]/ig,'');
  var html =
    "<html>\n"+
    "<head>\n"+
    "</head>\n"+
    "<body onload=\"document.getElementById('form').submit();\">\n"+
    "<p>Loading...</p>\n"+
    "<form id='form' action='"+url+"' method='post'>\n"+
    "<input type='hidden' name='targetReportID' id='targetReportID' value='"+targetReportID+"' />\n"+
    "<input type='hidden' name='targetID' id='targetID' value='"+targetID+"' />\n"+
    "<input type='hidden' name='selectID' id='selectID' value='"+selectID+"' />\n"+
    "<input type='hidden' name='filterField' id='filterField' value='"+filterField+"' />\n"+
    "<input type='hidden' name='filterExact' id='filterExact' value='"+filterExact+"' />\n"+
    "<input type='hidden' name='filterValue' id='filterValue' value='"+filterValue+"' />\n"+
    "</form>\n"+
    "</body>\n"+
    "</html>";
  var window_hd = popWin('',window_name,'scrollbars=1,resizable=1',width,height,'centre');
  window_hd.focus();
  window_hd.document.write(html);
  window_hd.document.close();
}

function popup_calendar_large(URL){
  popWin(
    URL+"?YYYY="+geid_val('YYYY')+"&MM="+geid_val("MM")+"&DD="+geid_val('DD'),
    'calendar_large','location=1,status=1,scrollbars=1,menubar=1,resizable=1',800,600,1
  );
}

function print_form(report_name,ID) {
  popWin(
    base_url+'print_form/'+report_name+'/'+ID, report_name.replace(/-/g,'_')+'_'+ID,'status=1, scrollbars=1,resizable=1',720,400,1
  );
}

function print_form_data() {
  var fields, frm, i, id;
  frm = geid('form');
  fields = [];
  for (i=0; i<frm.elements.length; i++) {
    id = frm.elements[i].id;
    switch (id) {
      case "command":
      case "goto":
      case "mode":
      case "submode":
      break;
      default:
        if (typeof(geid(id).name)!=='undefined') {
          fields.push(geid(id).name+'='+geid_val(id));
        }
      break;
    }
  }
  popWin(
    base_url+
    "?command=print_form_data"+
    "&"+fields.join('&'),'','status=1, scrollbars=1,resizable=1',600,600,1);
  return false;
}

function popup_map(system,page,ID){
  popWin(system+'/'+page+'?ID='+ID+'&print=1',ID,'status=1, scrollbars=1,resizable=1',600,600,1);
}

function popup_map_general(type,ID,field_info,field_lat,field_lon,field_area,width,height){
  var name, url;
  height =  (typeof height==='undefined' ? 600 : height);
  width =   (typeof width==='undefined' ? 600 : width);
  name =    ('map_'+type+'_'+ID).replace(/[ \,\-]/g,'_');
  url =
    base_url+'_map'+
    '?type='+type+
    '&ID='+ID+
    '&field_info='+field_info+
    '&field_lat='+field_lat+
    '&field_lon='+field_lon+
    '&field_area='+field_area+
    '&width='+width+
    '&height='+height;
  popWin(url,name,'status=1,resizable=1',width,height,1);
  return false;
}

function popup_map_general_maximize(type){
  var obj = $('#google_map_'+type+'_listing');
  var h = ($(window).height() -35)+'px';
  var w = ($(window).width() -50 -obj.width())+'px';
  $("#google_map_"+type+"_frame")[0].style.height = h;
  $("#google_map_"+type+"_frame")[0].style.width = ($(window).width()-10)+"px";
  $("#google_map_"+type)[0].style.height = h;
  $("#google_map_"+type)[0].style.width = w;
  if ($("#google_map_"+type+"_listing")){
    $("#google_map_"+type+"_listing")[0].style.height = h;
  }
}


function popup_help(page) {
  if (page===undefined) {
    popWin(base_url+'?mode=help','help','status=1, scrollbars=1,resizable=1',720,400,1);
  }
  else {
    popWin(base_url+'?mode=help&page='+page,'help','status=1, scrollbars=1,resizable=1',720,400,1);
  }
}

function popup_email_to_friend(url) {
  popWin(url,'email_to_a_friend','status=1, scrollbars=1,resizable=1',720,540,1);
  return false;
}

function print_friendly() {
  var theURL;
  if (window.location.search!=="") {
    theURL = window.location.href+"&print=1";
  }
  else {
    theURL =
      window.location.href.toString().split('#')[0]+"?print=1" +
      (geid('goto') && geid_val('goto')!=="" ? "&goto="+geid_val('goto') : "") +
      (geid('filterField') && geid_val('filterField')!=="" ? "&filterField="+geid_val('filterField') : "") +
      (geid('filterExact') && geid_val('filterExact')!=="" ? "&filterExact="+geid_val('filterExact') : "") +
      (geid('filterValue') && geid_val('filterValue')!=="" ? "&filterValue="+geid_val('filterValue') : "") +
      (geid('limit') && geid_val('limit')!=="" ? "&limit="+geid_val('limit') : "") +
      (geid('offset') && geid_val('offset')!=="" ? "&offset="+geid_val('offset') : "") +
      (geid('search_categories') && geid_val('search_categories')!=="" ? "&search_categories="+geid_val('search_categories') : "") +
      (geid('search_keywords') && geid_val('search_keywords')!=="" ? "&search_keywords="+geid_val('search_keywords') : "") +
      (geid('search_offset') && geid_val('search_offset')!=="" ? "&search_offset="+geid_val('search_offset') : "") +
      (geid('search_type') && geid_val('search_type')!=="" ? "&search_type="+geid_val('search_type') : "") +
      (geid('selectID') && geid_val('selectID')!=="" ? "&selectID="+geid_val('selectID') : "") +
      (geid('topbar_search') && geid_val('topbar_search')!=="" ? "&topbar_search="+geid_val('topbar_search') : "") +
      (geid('sortBy') && geid_val('sortBy')!=="" ? "&sortBy="+geid_val('sortBy') : "");
  }
  popWin(theURL,'printFriendly','scrollbars=1,resizable=1,status=1,toolbar=1,menubar=1',600,400,1);
}

function radio_group_get(name) {
  var element_arr, i;
  element_arr = document.getElementsByName(name);
  if(!element_arr) {
    return false;
  }
  for(i = 0; i < element_arr.length; i++) {
    if(element_arr[i].checked) {
      return element_arr[i].value;
    }
  }
  return false;
}

function radio_group_set(name,val) {
  var element_arr, i;
  element_arr = document.getElementsByName(name);
  if(!element_arr) {
    return false;
  }
  for(i = 0; i < element_arr.length; i++) {
    element_arr[i].checked = false;
    if (element_arr[i].value===val.toString()) {
      element_arr[i].checked = true;
      return true;
    }
  }
  return false;
}

function radio_groups_group_set(val,options_csv) {
  var i, options_arr;
  options_arr = options_csv.split(',');
  for (i=0; i<options_arr.length; i++) {
    if (geid('form')[options_arr[i]]) {
      if (val===options_arr[i]) {
        geid('form')[options_arr[i]][0].checked=0;
        geid('form')[options_arr[i]][1].checked=1;
      }
      else {
        geid('form')[options_arr[i]][0].checked=1;
        geid('form')[options_arr[i]][1].checked=0;
      }
    }
  }
}

function register_required(form) {
  if (form.NFirst.value==='' && form.NLast.value===''){
    alert('You must enter a name');
    return false;
  }
  if (form.PEmail.value==='') {
    alert('You must enter a contact email address');
    return false;
  }
  return true;
}

function report_filter_setup(){
  var i,j,k,id,filter,fn,reportID,type,types;
  types = ['g','s','p'];
  if (typeof report_filters==='object'){
    for(i in report_filters){
      if (typeof report_filters[i]==='object'){
        reportID = i;
        for(j=0; j<types.length; j++){
          type = types[j];
          if (typeof report_filters[reportID][type]==='object'){
            for(k=0; k<report_filters[reportID][type].length; k++){
              filter = report_filters[reportID][type][k];
              id = 'filters_for_report_'+reportID+'_'+filter.ID;
              fn = report_filter_setup_onclick(reportID,type,k,filter);
              if (geid(id)){
                geid(id).onclick = fn;
                geid(id).title= filter.title;
                if (filter.can_edit){
                  fn = report_filter_setup_onmouseover(reportID,type,k,filter);
                  geid(id).onmouseover = fn;
                  fn = report_filter_setup_onmouseout();
                  geid(id).onmouseout = fn;
                }
              }
              else {
                alert('id='+id);
              }
            }
          }
        }
      }
    }
  }
  if (typeof report_filters_sort==='object'){
    for(i in report_filters_sort){
      if (typeof report_filters_sort[i]==='object'){
        report_filter_setup_dragable(i);
      }
    }
  }
}

function report_filter_setup_dragable(reportID){
  var j, type;
  var types = ['g','s','p'];
  for(j=0; j<types.length; j++){
    type = types[j];
    if (report_filters_sort[reportID].g===1){
      $('#filters_for_report_'+reportID+'_global').sortable({
        axis: 'x',
        update: function(event,ui){
          report_filter_setup_ondrag(this,reportID,'global',event.target);
        }
      });
    }
    if (report_filters_sort[reportID].s===1){
      $('#filters_for_report_'+reportID+'_system').sortable({
        axis: 'x',
        update: function(event,ui){
          report_filter_setup_ondrag(this,reportID,'system',event.target);
        }
      });
    }
    if (report_filters_sort[reportID].p===1){
      $('#filters_for_report_'+reportID+'_person').sortable({
        axis: 'x',
        update: function(event,ui){
          report_filter_setup_ondrag(this,reportID,'person',event.target);
        }
      });
    }
  }
}

function report_filter_setup_ondrag(obj,reportID,type,item){
  var list, post_vars, xFn;
  $(obj).children().css({'color':'#c0c0c0','border-style':'dashed'});
  var items = $(obj).sortable('toArray');
  $.each(items,function(index,item){items[index] = item.split('_').pop();});
  seq = items.toString();
  window.focus();
  $.post(
    base_url,{
      command:          'report_filter_seq',
      mode:             type,
      targetReportID:   reportID,
      targetValue:      seq
    },
    function(result){
      $(obj).children().css({'color':'','border-style':'solid'});
    }
  );
  return false;
}

function report_filter_setup_onclick(reportID,type,k,filter){
  if(typeof report_filters[reportID][type][k].settings.ID==='undefined'){
    return function(){
      alert('This filter has no criteria. You should probably delete it.');
    };
  }
  if (report_filters[reportID][type][k].report===''){
    return function(){
      report_filter_set(reportID,filter);
      geid('form').submit();
    };
  }
  return function(){
    report_filter_set(reportID,filter);
    ajax_report(reportID,filter.report,'1','',filter.resource_url);
  };
}

function report_filter_setup_onmouseover(reportID,type,k,filter){
  return function(){
    if (!_contextActive){
       _CM.type='report_filter';
       _CM.ID=filter.ID;
       _CM_ID[1]=reportID;
       _CM_ID[2]=(k===0 ? 0 : 1);
       _CM_ID[3]=(k===report_filters[reportID][type].length-1 ? 0 : 1);
       _CM_ID[4]=type;
       _CM_ID[5]=filter.systemID;
    }
  };
}

function report_filter_setup_onmouseout(){
  return function(){
    _CM.type='';
  };
}

function report_filter_set(reportID,filter) {
  var fe, ff, fv, i;
  geid_set('filterField',filter.settings.criterion);
  geid_set('filterExact',filter.settings.matchmode);
  geid_set('filterValue',filter.settings.value);
  ff = geid('filterField_'+reportID);
  fe = geid('filterExact_'+reportID);
  fv = geid('filterValue_'+reportID);
  for (i=0; i<ff.options.length; i++) {
    if (ff.options[i].value===filter.settings.criterion) {
      ff.selectedIndex = i;
    }
  }
  for (i=0; i<fe.options.length; i++) {
    if (fe.options[i].value===filter.settings.matchmode) {
      fe.selectedIndex = i;
    }
  }
  fv.value=filter.settings.value;
}

function filterbar_disable_controls(reportID) {
  if (geid('btn_go_'+reportID))    { geid('btn_go_'+reportID).disabled=true; }
  if (geid('btn_clear_'+reportID)) { geid('btn_clear_'+reportID).disabled=true; }
  if (geid('btn_save_'+reportID))  { geid('btn_save_'+reportID).disabled=true; }
}

function filterbar_filterField_onchange(reportID) {
  if(geid_val('filterField_'+reportID)===''){
    geid('filterExact_'+reportID).selectedIndex=0;
    geid_set('filterExact','');
  }
  if(geid_val('filterField_'+reportID)!=='' && geid_val('filterExact_'+reportID)===''){
    geid('filterExact_'+reportID).selectedIndex=1;
    geid_set('filterExact','0');
  }
  geid_set('filterField',geid_val('filterField_'+reportID));
  return false;
}

function filterbar_filterExact_onchange(reportID) {
  geid_set('filterExact',geid_val('filterExact_'+reportID));
  if(geid_val('filterExact_'+reportID)===''){
    geid('filterField_'+reportID).selectedIndex=0;
  }
}

function filterbar_value_onblur(e,reportID,filterValueDefault){
  var field = geid('filterValue_'+reportID);
  if (field.value===''){
    field.value=filterValueDefault;
    field.style.color='#0000ff';
  }
}

function filterbar_value_onclick(e,reportID,filterValueDefault){
  var field = geid('filterValue_'+reportID);
  if (field.value===filterValueDefault){
    field.value='';
    field.style.color='#000000';
  }
}

function filterbar_value_onkeypress(e,reportID){
  var keynum = (window.event ? e.keyCode : e.which);
  if(keynum === 13) {
    geid_set('filterValue',geid_val('filterValue_'+reportID));
    geid('btn_go_'+reportID).focus();
    geid('btn_go_'+reportID).click();
    return false;
  }
  return true;
}

function filterbar_go_onclick(reportID,report_name,toolbar,ajax_mode,page_url){
  filterbar_disable_controls(reportID);
  geid_set('filterField',geid_val('filterField_'+reportID));
  geid_set('filterExact',geid_val('filterExact_'+reportID));
  geid_set('filterValue',geid_val('filterValue_'+reportID));
  geid_set('offset',geid_val('offset_'+reportID));
  geid_set('limit',geid_val('limit_'+reportID));
  geid_set('sortBy',geid_val('sortBy_'+reportID));
  if (report_name) {
    ajax_report(reportID,report_name,toolbar,ajax_mode,page_url);
  }
  else {
    geid('form').submit();
  }
}

function filterbar_clear_onclick(reportID,report_name,toolbar,ajax_mode,page_url){
  filterbar_disable_controls(reportID);
  geid_set('limit',10);
  geid_set('offset',0);
  geid_set('filterExact','');
  geid_set('filterField','');
  geid_set('filterValue','');
  geid_set('filterExact_'+reportID,'');
  geid_set('filterValue_'+reportID,'');
  geid_set('filterField_'+reportID,'');
  if (geid('limit_'+reportID) && geid('limit_'+reportID).selectedIndex) { geid('limit_'+reportID).selectedIndex=0; }
  if (geid('offset_'+reportID) && geid('offset_'+reportID).selectedIndex) { geid('offset_'+reportID).selectedIndex=0;}
  if (report_name) {
    ajax_report(reportID,report_name,toolbar,ajax_mode,page_url);
  }
  else {
    geid('form').submit();
  }
}

function filterbar_save_onclick(reportID,report_name,toolbar){
  var h, j;
  h =
    "<div style='padding:4px;'>Enter name for new Preset Filter"+
    "<div style='padding-top:10px;'>"+
    "<table class='minimal' summary='Grid layout for popup form'>\n"+
    "  <tr>\n"+
    "    <td style='width:50px;'>Name</td>\n"+
    "    <td><input type='text' id='filter_name' style='width:100px;' value=''"+
    " onkeyup=\"geid('btn_ok').disabled = this.value.length==0\""+
    " onkeypress=\"return keytest_enter_transfer(event,'btn_ok')\" class='formField fl'"+
    "/></td>\n"+
    "  </tr>\n"+
    "</table>\n"+
    "</div></div>";
  j =
    "if(geid_val('filter_name')==''){return false;}"+
    "filterbar_disable_controls('"+reportID+"');"+
    "geid_set('submode','filter_add');"+
    "geid_set('targetValue',geid_val('filter_name'));"+
    "geid_set('targetReportID','"+reportID+"');"+
    "geid_set('filterValue',geid_val('filterValue_"+reportID+"'));"+
    "geid('form').submit();";
  popup_dialog("Filter Save",h,200,260,'OK','Cancel',j,'filter_name');
  geid('btn_ok').disabled=true;
}

function select_item(ID){
  geid('selectID').value = ID;
  geid('form').submit();
}

function select_YYYY_MM(YYYY,MM,targetReportName) {
  geid('YYYY').value =    YYYY;
  geid('MM').value =      MM;
  geid('anchor').value =  targetReportName;
  geid('form').submit();
}

function setBackground(targDiv,color) {
  if (!isW3C) {
    return;
  }
  var div = geid(targDiv);
  if (div) {
    div.style.background = color;
  }
}

function setBlock(targDiv,show) {
  if (!isW3C) {
    return;
  }
  var div = geid(targDiv);
  if (div) {
    div.style.display = (show ? "block" : "none");
  }
}

function setDisplay(targDiv,show) {
  if (!isW3C) {
    return;
  }
  var div = geid(targDiv);
  if (div) {
    div.style.display = (show ? "" : "none");
  }
}

function setOnStage(targDiv,show) {
  if (!isW3C) {
    return;
  }
  var div = geid(targDiv);
  if (div) {
    div.style.left = (show ? '0px' : '-10000px');
  }
}

function signup_required(form) {
  if ((form.PUsername && form.PUsername.value==='')) {
    alert('You must enter a requested username');
    return false;
  }
  if ((form.PEmail && form.PEmail.value==='')) {
    alert('You must enter a valid email address');
    return false;
  }
  return true;
}

function show_section(sections_arr,section,_default) {
  var _section, div, found_it, i;
  if (!geid('section_'+section+'_heading') && typeof _default==='undefined'){
    return false;
  }
  found_it = false;
  geid('selected_section').value=section;
  tcal_hideAll();
  if (typeof section!='undefined'){
    for (i=0; i<sections_arr.length; i++) {
      _section = sections_arr[i];
      div = geid('section_'+_section+'_heading');
      if (div) {
        setDisplay('section_'+_section,(section===_section));
        found_it = true;
        div.className = (section===_section ? 'tab_selected' : 'tab');
      }
    }
  }
  if (!found_it){
    show_section(sections_arr,_default)
  }
  return true;
}

function show_section_tab(sections_arr,section,_default) {
  // replacement for show_section() - this works on absolutely positioned elements
  var _section, div, found_it, i;
  if (!geid('section_'+section+'_heading') && typeof _default==='undefined'){
    return false;
  }
  found_it = false;
  geid('selected_section').value=section;
  tcal_hideAll();
  if (typeof section!='undefined'){
    for (i=0; i<sections_arr.length; i++) {
      _section = sections_arr[i];
      div = geid('section_'+_section+'_heading');
      if (div) {
        setOnStage('section_'+_section,(section===_section));
        found_it = true;
        div.className = (section===_section ? 'tab_selected' : 'tab');
      }
    }
    $('#section_'+section).parent().height($('#section_'+section).height());
  }
  if (!found_it){
    show_section_tab(sections_arr,_default)
  }
  return true;
}

function show_section_onhashchange_setup(sections_arr){
  if ('onhashchange' in window){
    window.onhashchange = function(){
      var section = window.location.hash.split('#')[1];
      show_section_tab(sections_arr,section);
    }
  }
  else {
    var storedHash = window.location.hash;
    window.setInterval(function(){
      if (window.location.hash != storedHash){
        storedHash = window.location.hash;
        var section = storedHash.split('#')[1];
        show_section_tab(sections_arr,section);
      }
    },100);
  }
}

function LTrim(str,chars) {
  chars = chars || "\\s";
  return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function RTrim(str,chars) {
  chars = chars || "\\s";
  return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}

function Trim(str,chars) {
  return RTrim(LTrim(str,chars),chars);
}


function round(number,X) {
  X = (!X ? 2 : X);
  return Math.round(number*Math.pow(10,X))/Math.pow(10,X);
}

function search_offset(offset,type){
  geid_set('search_offset',offset);
  geid_set('search_type',type);
  geid('form').submit();
  return false;
}

function search_setup_date_range() {
  geid('_search_date_start').innerHTML = date_selector_draw('_search_date_start',geid_val('search_date_start'));
  geid('_search_date_end').innerHTML =   date_selector_draw('_search_date_end',geid_val('search_date_end'));
}

function search_results_go() {
  var end, end_val, i, selector, sites_arr, start, start_val, tmp;
  if (geid('search_sites_selector')) {
    sites_arr = [];
    selector =  geid('search_sites_selector');
    for (i=0; i<selector.options.length; i++) {
      if (selector.options[i].selected){
        sites_arr.push(selector.options[i].value);
      }
    }
    geid_set('search_sites',sites_arr.join(','));
  }
  start = geid_val('_search_date_start_yyyy')+"-"+geid_val('_search_date_start_mm')+"-"+geid_val('_search_date_start_dd');
  end =   geid_val('_search_date_end_yyyy')+"-"+geid_val('_search_date_end_mm')+"-"+geid_val('_search_date_end_dd');
  start_val = geid_val('_search_date_start_yyyy')*10000 + geid_val('_search_date_start_mm')*100 + geid_val('_search_date_start_dd');
  end_val =   geid_val('_search_date_end_yyyy')*10000 + geid_val('_search_date_end_mm')*100   + geid_val('_search_date_end_dd');
  if (start_val>end_val && end_val!==0){
    tmp = start; start = end; end = tmp;
  }
  geid_set('mode','');
  geid_set('search_offset','0');
  geid_set('search_categories',geid('_search_categories') ? geid_val('_search_categories') : "");
  geid_set('search_date_start',start);
  geid_set('search_date_end',  end);
  geid_set('search_keywords',geid('_search_keywords') ? geid_val('_search_keywords') : "");
  geid_set('search_name',geid('_search_name') ? geid_val('_search_name') : "");
  geid_set('search_text',geid_val('_search_text'));
  geid_set('search_type',geid_val('_search_type'));
  geid('form').submit();
}

function search_go() {
  geid_set('goto','search_results');
  geid_set('mode','');
  geid_set('search_offset','0');
  geid_set('search_type','*');
  geid('form').submit();
}

function textCounter(field, countfield, maxlimit) {
  if (field.value.length > maxlimit){
    field.value = field.value.substring(0, maxlimit);
  }
  else{
    countfield.value = maxlimit - field.value.length;
  }
}

function topbar_search_go() {
  geid_set('search_text',geid_val('topbar_search'));
  search_go();
}

function twitter_profile(args){
  var height, items_background, items_links, items_text, panel_background, panel_text, user, width;
  height =           (typeof args.height!=='undefined' ? args.height : 300);
  items_background = (typeof args.items_background!=='undefined' ? args.items_background : '#000000');
  items_links =      (typeof args.items_links!=='undefined' ? args.items_links : '#4aed05');
  items_text =       (typeof args.items_text!=='undefined' ? args.items_text : '#ffffff');
  panel_background = (typeof args.panel_background!=='undefined' ? args.panel_background : '#333333');
  panel_text =       (typeof args.panel_text!=='undefined' ? args.panel_text : '#ffffff');
  user =             (typeof args.user!=='undefined' ? args.user : 'twitter');
  width =            (typeof args.width!=='undefined' ? args.width : 250);
  new TWTR.Widget({
    version: 2,
    type: 'profile',
    rpp: 4,
    interval: 6000,
    width: width,
    height: height,
    theme: {
      shell: {
        background: panel_background,
        color: panel_text
      },
      tweets: {
        background: items_background,
        color: items_text,
        links: items_links
      }
    },
    features: {
      scrollbar: false,
      loop: false,
      live: false,
      hashtags: true,
      timestamp: true,
      avatars: false,
      behavior: 'all'
    }
  }).render().setUser(user).start();
}

function two_dp(amount){
  var i = parseFloat(amount);
  if(isNaN(i)) { i = 0.00; }
  var minus = '';
  if(i < 0) { minus = '-'; }
  i = Math.abs(i);
  i = parseInt((i + 0.005) * 100,10);
  i = i / 100;
  var s = ''+i;
  if(s.indexOf('.') < 0) { s += '.00'; }
  if(s.indexOf('.') === (s.length - 2)) { s += '0'; }
  s = minus + s;
  return s;
}

function payment_method_change(
    disable_cc, id_method, id_card_name, id_card_number, id_card_exp_mm, id_card_exp_yy, id_card_cvv
) {
  if (geid(id_card_name)){
    geid(id_card_name).disabled=disable_cc;
    geid(id_card_name).style.backgroundColor=(disable_cc ? '#e0e0e0' : '');
  }
  if (geid(id_card_number)){
    geid(id_card_number).disabled=disable_cc;
    geid(id_card_number).style.backgroundColor=(disable_cc ? '#e0e0e0' : '');
  }
  if (geid(id_card_exp_mm)){
    geid(id_card_exp_mm).disabled=disable_cc;
    geid(id_card_exp_mm).style.backgroundColor=(disable_cc ? '#e0e0e0' : '');
  }
  if (geid(id_card_exp_yy)){
    geid(id_card_exp_yy).disabled=disable_cc;
    geid(id_card_exp_yy).style.backgroundColor=(disable_cc ? '#e0e0e0' : '');
  }
  if (geid(id_card_cvv)){
    geid(id_card_cvv).disabled=disable_cc;
    geid(id_card_cvv).style.backgroundColor=(disable_cc ? '#e0e0e0' : '');
  }
}

function validate_payment_details(err_arr,id_method,id_card_name,id_card_number,id_card_ex_mm,id_card_exp_yy) {
  var n = err_arr.length;
  if (geid(id_method).value===''){
    err_arr[n++]= pad(''+n+')',4) +"Payment Method is missing";
  }
  if (geid_val(id_method).indexOf('Pay Pal') === -1) {
	  if (geid(id_card_name).value ===''){
	    err_arr[n++]= pad(''+n+')',4) +"CardHolder Name is missing";
	  }
	  if (geid(id_card_number).value.replace(/[^0-9]+/ig,'')===''){
	    err_arr[n++]=pad(''+n+')',4)+"Credit card number missing";
	  }
	  else {
	    if (geid(id_card_number).value.replace(/[^0-9 ]+/ig,'')!==geid(id_card_number).value){
	      err_arr[n++]=pad(''+n+')',4)+"Credit card number contains invalid characters";
	    }
	    else {
	      if (geid(id_card_number).value.replace(/[^0-9]+/ig,'').length!==16) {
	//        err_arr[n++]= pad(''+n+')',4)+"Credit card number must have 16 digits";
	      }
	    }
	  }
	  if (geid(id_card_ex_mm).value.length===0 || geid(id_card_ex_mm).value.replace(/[^0-9]+/ig,'')!==geid(id_card_ex_mm).value || parseFloat(geid(id_card_ex_mm).value)<1 || parseFloat(geid(id_card_ex_mm).value)>12) {
	    err_arr[n++]= pad(''+n+')',4)+"Expiry Month value must be between 01 and 12";
	  }
	  var this_year=new Date().getUTCFullYear().toString().substr(2,2);
	  if (geid(id_card_exp_yy).value.length===0 || geid(id_card_exp_yy).value.replace(/[^0-9]+/ig,'')!==geid(id_card_exp_yy).value || parseFloat(geid(id_card_exp_yy).value)<parseFloat(this_year) || parseFloat(geid(id_card_exp_yy).value)>(parseFloat(this_year)+10)) {
	    err_arr[n++]= pad(''+n+')',4)+"Expiry Year value outside acceptable range ("+this_year+" to "+(parseFloat(this_year)+10)+")";
	  }
	}
  return err_arr;
}

function version(ver) {
  var name = ver.replace(/\./g,'_');
  var site = (system_family.toLowerCase()==='ximmix' ? 'http://www.auroraonline.com' : 'http://www.ecclesiact.com');
  popWin(site+'/build/'+ver+'?print=1',name,'scrollbars=1,resizable=1,status=0',1000,800,'centre');
}

function video_setup(id,url){
  var div, html, width, height, tit;e;
  html =        $('#'+id)[0].innerHTML;
  if (html.substr(0,6)==='<IFRAME '){
    // was mangled by IE8 and below, so recreate from scratch
    width =   html.split('width=')[1].split(' ')[0];
    height =  html.split('height=')[1].split(' ')[0];
    title =   html.split('title=')[1].split(' ')[0];
    html =
      "<iframe class=\"youtube-player\""+
      " allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\"" +
      " allowfullscreen" +
      " type=\"text/html\""+
      " title=\""+title+"\""+
      " width=\""+width+"\""+
      " height=\""+height+"\""+
      " src=\""+url+"\""+
      " frameborder=\"0\"></iframe>";
      $('#'+id)[0].innerHTML = html;
    return false;
  }
  html =    html.replace(/(\b(http|https):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig,url+'?wmode=transparent&autoplay=1&rel=0');
  $('#'+id)[0].innerHTML = html;
  return false;
}


function view_event_registrants(ID,width,height) {
  popWin(
    base_url+'report/event_registrants/?print=1&selectID='+ID,'eventRegistrants','status=1, scrollbars=1,resizable=1',width,height,1
  );
}

function view_credit_memo(ID,width,height) {
  popWin(
    base_url+'view_credit_memo/?print=2&ID='+ID,'','status=1,scrollbars=1,resizable=1,menubar=1',width,height,1
  );
}

function view_order_details(ID,width,height) {
  popWin(
    base_url+'view_order/?print=2&ID='+ID,'','status=1,scrollbars=1,resizable=1,menubar=1',width,height,1
  );
}

function widget_toggle(ID){
  geid(ID+"_show").style.display=geid(ID).style.display;
  geid(ID).style.display=(geid(ID).style.display==='' ? 'none' : '');
  geid(ID+"_hide").style.display=geid(ID).style.display;
}

/**
 * COMMON DHTML FUNCTIONS (comments by original author)
 *
 * These are handy functions I use all the time.
 * By Seth Banks (webmaster at subimage dot com)
 * http://www.subimage.com/
 * Up to date code can be found at http://www.subimage.com/dhtml/
 * This code is free for you to use anywhere, just keep this comment block.
 */
function addEvent(obj, evType, fn){
  if (typeof obj!=='object'){
    if (typeof console!=='undefined'){
      console.error(
        "addEvent was called without a valid object by\n  1. " + arguments.callee.caller.toString()+'\n  2. '+ arguments.callee.caller.caller.toString()+'\n  3. '+ arguments.callee.caller.caller.caller.toString()
      );
    }
    return false;
  }
  if (obj.addEventListener){
    obj.addEventListener(evType, fn, true);
    return true;
  }
  if (obj.attachEvent) {
    var r = obj.attachEvent("on"+evType, fn);
    return r;
  }
  return false;
}

function removeEvent(obj, evType, fn, useCapture){
  if (obj.removeEventListener){
    obj.removeEventListener(evType, fn, useCapture);
    return true;
  } else if (obj.detachEvent){
    var r = obj.detachEvent("on"+evType, fn);
    return r;
  } else {
    alert("Handler could not be removed");
    return false;
  }
}

function cal_picker(field) {
  var DD, MM, YYYY, YYYYMMDD;
  YYYYMMDD = geid(field).value;
  YYYY = YYYYMMDD.substr(0,4);
  MM = YYYYMMDD.substr(5,2);
  DD = YYYYMMDD.substr(8,2);
  popWin(
    base_url+'?command=cal_picker&field='+field+
      (YYYY!=='' ? '&YYYY='+YYYY : '')+
      (MM!=='' ? '&MM='+MM : '')+
      (DD!=='' ? '&DD='+DD : ''),
    'popCalPicker','scrollbars=0,resizable=1,status=1',220,215,'centre');
  return false;
}
function cal_set_date(YYYYMMDD,field) {
  window.opener.geid_set(field,YYYYMMDD);
  window.close();
}

/* -----------------------------------------------------------
 * Copyright Mihai Bazon, 2002-2005  |  www.bazon.net/mishoo
 * -----------------------------------------------------------
 *  From http://www.dynarch.com/projects/calendar - LGPL
 *  The DHTML Calendar, version 1.0 "It is happening again"
 *
 *  2008-12-30 (MFrancis)
 *    1) Many changes to better pass JSLint, remove 'with' keywords and properly bracket everything.
 *    2) Added new date proptotype method date.toISO()
 *    3) Incorporated calendar-setup.js and calendar-en.js (yes I know that's not always a good idea)
 *    4) Added ability to set up tooltip handler
 *
 * This script is distributed under the GNU Lesser General Public License.
 * Read the entire license text here: http://www.gnu.org/licenses/lgpl.html
 */

// global object that remembers the calendar
window._dynarch_popupCalendar = null;

// $Id: calendar.js,v 1.51 2005/03/07 16:44:31 mishoo Exp $
/** The Calendar object constructor. */

function calendar_changed_fn(calendar) {
  if (geid_val('MM')!=calendar.date.getMonth()+1 || geid_val('YYYY')!=calendar.date.getFullYear()){
    geid_set('DD',calendar.date.getDate());
    geid_set('MM',calendar.date.getMonth()+1);
    geid_set('YYYY',calendar.date.getFullYear());
    geid('form').submit();
  }
  else {
    var Obj_CE = new cal_events(calendar.date.toISO());
    Obj_CE.draw(0);
  }
}

function calendar_changed_admin_fn(calendar) {
  if (geid_val('MM')!=calendar.date.getMonth()+1 || geid_val('YYYY')!=calendar.date.getFullYear()){
    geid_set('DD',calendar.date.getDate());
    geid_set('MM',calendar.date.getMonth()+1);
    geid_set('YYYY',calendar.date.getFullYear());
    geid('form').submit();
  }
  else {
    var Obj_CE = new cal_events(calendar.date.toISO());
    Obj_CE.draw(1);
  }
}


function calendar_status_fn(date, yyyy, mm, dd) {
  var m = calendar_special_days[date.toISO()];
  if (!m){
    return;
  }
  if (m.length==1){
    return 'cal_has_event';
  }
  return 'cal_has_events';
};

function calendar_tooltip_fn(date, yyyy, mm, dd) {
  var m = calendar_special_days[date.toISO()];
  if (!m){
    return;
  }
  return (m.length==1 ? 'EVENT:\n ' : ' EVENTS: ('+m.length+')\n ') + m.join('\n ');
};

function Calendar(firstDayOfWeek, dateStr, onSelected, onClose) {
  var ar, i;
  // member variables
  this.activeDiv =      null;
  this.currentDateEl =  null;
  this.getDateStatus =  null;
  this.getDateToolTip =	null;
  this.getDateText =    null;
  this.timeout =        null;
  this.onSelected =     onSelected || null;
  this.onClose =        onClose || null;
  this.dragging =       false;
  this.hidden =         false;
  this.link =           false;
  this.minYear =        1970;
  this.maxYear =        2050;
  this.dateFormat =     Calendar._TT.DEF_DATE_FORMAT;
  this.ttDateFormat =   Calendar._TT.TT_DATE_FORMAT;
  this.isPopup =        true;
  this.weekNumbers =    true;
  this.firstDayOfWeek = (typeof firstDayOfWeek === "number" ? firstDayOfWeek : Calendar._FD); // 0 for Sunday, 1 for Monday, etc.
  this.showsOtherMonths = false;
  this.dateStr =        dateStr;
  this.ar_days =        null;
  this.showsTime =      false;
  this.time24 =         true;
  this.yearStep =       1;
  this.hiliteToday =    true;
  this.multiple =       null;

  // HTML elements
  this.table =          null;
  this.element =        null;
  this.tbody =          null;
  this.firstdayname =   null;

  // Combo boxes
  this.monthsCombo =    null;
  this.yearsCombo =     null;
  this.hilitedMonth =   null;
  this.activeMonth =    null;
  this.hilitedYear =    null;
  this.activeYear =     null;

  // Information
  this.dateClicked =    false;
  // one-time initializations
  if (typeof Calendar._SDN === "undefined") {
    // table of short day names
    if (typeof Calendar._SDN_len === "undefined"){
      Calendar._SDN_len = 3;
    }
    ar = [];
    for (i = 8; i > 0;) {
      ar[--i] = Calendar._DN[i].substr(0, Calendar._SDN_len);
    }
    Calendar._SDN = ar;
    // table of short month names
    if (typeof Calendar._SMN_len === "undefined"){
      Calendar._SMN_len = 3;
    }
    ar = [];
    for (i = 12; i > 0;) {
      ar[--i] = Calendar._MN[i].substr(0, Calendar._SMN_len);
    }
    Calendar._SMN = ar;
  }
}

/**
 *  This function "patches" an input field (or other element) to use a calendar
 *  widget for date selection.
 *
 *  The "params" is a single object that can have the following properties:
 *
 *    prop. name   | description
 *  -------------------------------------------------------------------------------------------------
 *   align         | alignment (default: "Br"); if you don't know what's this see the calendar documentation
 *   button        | ID of a button or other element that will trigger the calendar
 *   cache         | if "true" (but default: "false") it will reuse the same calendar object, where possible
 *   daFormat      | the date format that will be used to display the date in displayArea
 *   date          | the date that the calendar will be initially displayed to
 *   disableFunc   | function that receives a JS Date object and should return true if that date has to be disabled in the calendar
 *   displayArea   | the ID of a DIV or other element to show the date
 *   electric      | if true (default) then given fields/date areas are updated for each move; otherwise they're updated only on close
 *   eventName     | event that will trigger the calendar, without the "on" prefix (default: "click")
 *   firstDay      | numeric: 0 to 6.  "0" means display Sunday first, "1" means display Monday first, etc.
 *   flat          | null or element ID; if not null the calendar will be a flat calendar having the parent with the given ID
 *   flatCallback  | function that receives a JS Date object and returns an URL to point the browser to (for flat calendar)
 *   ifFormat      | date format that will be stored in the input field
 *   inputField    | the ID of an input field to store the date
 *   onClose       | function that gets called when the calendar is closed.  [default]
 *   onSelect      | function that gets called when a date is selected.  You don't _have_ to supply this (the default is generally okay)
 *   onUpdate      | function that gets called after the date is updated in the input field.  Receives a reference to the calendar.
 *   position      | configures the calendar absolute position; default: null
 *   range         | array with 2 elements.  Default: [1900, 2999] -- the range of years available
 *   showOthers    | if "true" (but default: "false") it will show days from other months too
 *   showsTime     | default: false; if true the calendar will include a time selector
 *   singleClick   | (true/false) wether the calendar is in single click mode or not (default: true)
 *   step          | configures the step of the years in drop-down boxes; default: 2
 *   timeFormat    | the time format; can be "12" or "24", default is "12"
 *   weekNumbers   | (true/false) if it's true (default) the calendar will display week numbers
 *
 *  None of them is required, they all have default values.  However, if you
 *  pass none of "inputField", "displayArea" or "button" you'll get a warning
 *  saying "nothing to setup".
 */
Calendar.setup = function (params) {
  function onSelect(cal) {
    var p, update;
    p = cal.params;
    update = (cal.dateClicked || p.electric);
    if (update && p.inputField) {
      p.inputField.value = cal.date.print(p.ifFormat);
      if (typeof p.inputField.onchange === "function") {
        p.inputField.onchange();
      }
    }
    if (update && p.displayArea) {
      p.displayArea.innerHTML = cal.date.print(p.daFormat);
    }
    if (update && typeof p.onUpdate === "function") {
      p.onUpdate(cal);
    }
    if (update && p.flat) {
      if (typeof p.flatCallback === "function") {
        p.flatCallback(cal);
      }
    }
    if (update && p.singleClick && cal.dateClicked) {
      cal.callCloseHandler();
    }
  }

  function param_default(pname, def) {
    if (typeof params[pname] === "undefined") {
      params[pname] = def;
    }
  }
  var cal, d, dateEl, dateFmt, ds, i, mustCreate, triggerEl, tmp;
  param_default("disableFunc",		null); // Put first as others may use it

  param_default("align",		"Br");
  param_default("button",		null);
  param_default("cache",		false);
  param_default("daFormat",		"%Y/%m/%d");
  param_default("date",			null);
  param_default("dateStatusFunc",	params.disableFunc);	// takes precedence if both are defined
  param_default("dateText",		null);
  param_default("dateToolTipFunc",	params.disableFunc);	// Added by Martin
  param_default("displayArea",		null);
  param_default("electric",		true);
  param_default("eventName",		"click");
  param_default("firstDay",		null);
  param_default("flat",			null);
  param_default("flatCallback",		null);
  param_default("ifFormat",		"%Y/%m/%d");
  param_default("link_enlarge",		null);      // Added by M Francis
  param_default("link_enlarge_popup",	null);  // Added by M Francis
  param_default("link_help",		null);      // Added by M Francis
  param_default("inputField",		null);
  param_default("multiple",		null);
  param_default("onClose",		null);
  param_default("onSelect",		null);
  param_default("onUpdate",		null);
  param_default("position",		null);
  param_default("range",		[2005, 2030]);
  param_default("showOthers",		false);
  param_default("showsTime",		false);
  param_default("singleClick",		true);
  param_default("step",			1);
  param_default("timeFormat",		"24");
  param_default("weekNumbers",		true);
  tmp = ["inputField", "displayArea", "button"];
  for (i in tmp) {
    if (typeof params[tmp[i]] === "string") {
      params[tmp[i]] = document.getElementById(params[tmp[i]]);
    }
  }
  if (!(params.flat || params.multiple || params.inputField || params.displayArea || params.button)) {
    alert("Calendar.setup:\n  Nothing to setup (no fields found).  Please check your code");
    return false;
  }

  if (params.flat !== null) {
    if (typeof params.flat === "string") {
      params.flat = document.getElementById(params.flat);
    }
    if (!params.flat) {
      alert("Calendar.setup:\n  Flat specified but can't find parent.");
      return false;
    }
    cal = new Calendar(params.firstDay, params.date, params.onSelect || onSelect);
    cal.showsOtherMonths = params.showOthers;
    cal.showsTime = params.showsTime;
    cal.time24 = (params.timeFormat === "24");
    cal.params = params;
    cal.weekNumbers = params.weekNumbers;
    cal.setRange(params.range[0], params.range[1]);
    cal.setDateStatusHandler(params.dateStatusFunc);
    cal.setDateToolTipHandler(params.dateToolTipFunc);
    cal.setLinkEnlarge(params.link_enlarge);
    cal.setLinkEnlargePopup(params.link_enlarge_popup);
    cal.setLinkHelp(params.link_help);
    cal.getDateText = params.dateText;
    if (params.ifFormat) {
      cal.setDateFormat(params.ifFormat);
    }
    if (params.inputField && typeof params.inputField.value === "string") {
      cal.parseDate(params.inputField.value);
    }
    cal.create(params.flat);
    cal.show();
    return cal;
  }
  triggerEl = params.button || params.displayArea || params.inputField;
  triggerEl["on" + params.eventName] = function() {
    dateEl = params.inputField || params.displayArea;
    dateFmt = params.inputField ? params.ifFormat : params.daFormat;
    mustCreate = false;
    cal = window.calendar;
    if (dateEl) {
      params.date = Date.parseDate(dateEl.value || dateEl.innerHTML, dateFmt);
    }
    if (!(cal && params.cache)) {
      cal = new Calendar(
        params.firstDay,
        params.date,
        params.onSelect || onSelect,
        params.onClose || function(cal) { cal.hide(); }
      );
      window.calendar = cal;
      cal.showsTime = params.showsTime;
      cal.time24 = (params.timeFormat === "24");
      cal.weekNumbers = params.weekNumbers;
      mustCreate = true;
    }
    else {
      if (params.date) {
        cal.setDate(params.date);
      }
      cal.hide();
    }
    if (params.multiple) {
      cal.multiple = {};
      for (i = params.multiple.length; --i >= 0;) {
        d = params.multiple[i];
        ds = d.print("%Y%m%d");
        cal.multiple[ds] = d;
      }
    }
    cal.showsOtherMonths = params.showOthers;
    cal.yearStep = params.step;
    cal.setRange(params.range[0], params.range[1]);
    cal.params = params;
    cal.setDateStatusHandler(params.dateStatusFunc);
    cal.setDateToolTipHandler(params.dateToolTipFunc);
    cal.getDateText = params.dateText;
    cal.setDateFormat(dateFmt);
    if (mustCreate) {
      cal.create();
    }
    cal.refresh();
    if (!params.position) {
      cal.showAtElement(params.button || params.displayArea || params.inputField, params.align);
    }
    else {
      cal.showAt(params.position[0], params.position[1]);
    }
    return false;
  };
  return window.calendar;
};

// ** constants

/// "static", needed for event handlers.
Calendar._C = null;

/// detect a special case of "web browser"
Calendar.is_ie = ( /msie/i.test(navigator.userAgent) && !/opera/i.test(navigator.userAgent) );
Calendar.is_ie5 = ( Calendar.is_ie && /msie 5\.0/i.test(navigator.userAgent) );
Calendar.is_opera = /opera/i.test(navigator.userAgent);
Calendar.is_khtml = /Konqueror|Safari|KHTML/i.test(navigator.userAgent);

Calendar.getAbsolutePos = function(el) {
  var is_div, r, tmp, SL, ST;
  SL = 0;
  ST = 0;
  is_div = /^div$/i.test(el.tagName);
  if (is_div && el.scrollLeft){
    SL = el.scrollLeft;
  }
  if (is_div && el.scrollTop){
    ST = el.scrollTop;
  }
  r = { x: el.offsetLeft - SL, y: el.offsetTop - ST };
  if (el.offsetParent) {
    tmp = this.getAbsolutePos(el.offsetParent);
    r.x += tmp.x;
    r.y += tmp.y;
  }
  return r;
};

Calendar.isRelated = function (el, evt) {
  var related, type;
  related = evt.relatedTarget;
  if (!related) {
    type = evt.type;
    if (type === "mouseover") {
      related = evt.fromElement;
    } else if (type === "mouseout") {
      related = evt.toElement;
    }
  }
  while (related) {
    if (related === el) {
      return true;
    }
    related = related.parentNode;
  }
  return false;
};

Calendar.removeClass = function(el, className) {
  var ar, cls, i;
  if (!(el && el.className)) {
    return;
  }
  cls = el.className.split(" ");
  ar = [];
  for (i = cls.length; i > 0;) {
    if (cls[--i] !== className) {
      ar[ar.length] = cls[i];
    }
  }
  el.className = ar.join(" ");
};

Calendar.addClass = function(el, className) {
  Calendar.removeClass(el, className);
  el.className += " " + className;
};

// FIXME: the following 2 functions totally suck, are useless and should be replaced immediately.
Calendar.getElement = function(ev) {
  var f;
  f = Calendar.is_ie ? window.event.srcElement : ev.currentTarget;
  while (f.nodeType !== 1 || /^div$/i.test(f.tagName)){
    f = f.parentNode;
  }
  return f;
};

Calendar.getTargetElement = function(ev) {
  var f;
  f = Calendar.is_ie ? window.event.srcElement : ev.target;
  while (f.nodeType !== 1){
    f = f.parentNode;
  }
  return f;
};

Calendar.stopEvent = function(ev) {
  if (!ev) { ev = window.event; }
  if (Calendar.is_ie) {
    ev.cancelBubble = true;
    ev.returnValue = false;
  }
  else {
    ev.preventDefault();
    ev.stopPropagation();
  }
  return false;
};

Calendar.addEvent = function(el, evname, func) {
  if (el.attachEvent) { // IE
    el.attachEvent("on" + evname, func);
  }
  else if (el.addEventListener) { // Gecko / W3C
    el.addEventListener(evname, func, true);
  }
  else {
    el["on" + evname] = func;
  }
};

Calendar.removeEvent = function(el, evname, func) {
  if (el.detachEvent) { // IE
    el.detachEvent("on" + evname, func);
  }
  else if (el.removeEventListener) { // Gecko / W3C
    el.removeEventListener(evname, func, true);
  }
  else {
    el["on" + evname] = null;
  }
};

Calendar.createElement = function(type, parent) {
  var el = null;
  if (document.createElementNS) {
    // use the XHTML namespace; IE won't normally get here unless
    // _they_ "fix" the DOM2 implementation.
    el = document.createElementNS("http://www.w3.org/1999/xhtml", type);
  } else {
    el = document.createElement(type);
  }
  if (typeof parent !== "undefined") {
    parent.appendChild(el);
  }
  return el;
};

// END: UTILITY FUNCTIONS

// BEGIN: CALENDAR STATIC FUNCTIONS

/** Internal -- adds a set of events to make some element behave like a button. */
Calendar._add_evs = function(el) {
  Calendar.addEvent(el, "mouseover", Calendar.dayMouseOver);
  Calendar.addEvent(el, "mousedown", Calendar.dayMouseDown);
  Calendar.addEvent(el, "mouseout", Calendar.dayMouseOut);
  if (Calendar.is_ie) {
    Calendar.addEvent(el, "dblclick", Calendar.dayMouseDblClick);
    el.setAttribute("unselectable", true);
  }
};
Calendar.findMonth = function(el) {
  if (typeof el.month !== "undefined") {
    return el;
  }
  else if (typeof el.parentNode.month !== "undefined") {
    return el.parentNode;
  }
  return null;
};

Calendar.findYear = function(el) {
  if (typeof el.year !== "undefined") {
    return el;
  }
  else if (typeof el.parentNode.year !== "undefined") {
    return el.parentNode;
  }
  return null;
};

Calendar.showMonthsCombo = function () {
  var cal = Calendar._C;
  if (!cal) {
    return false;
  }
  var cd = cal.activeDiv;
  var mc = cal.monthsCombo;
  if (cal.hilitedMonth) {
    Calendar.removeClass(cal.hilitedMonth, "hilite");
  }
  if (cal.activeMonth) {
    Calendar.removeClass(cal.activeMonth, "active");
  }
  var mon = cal.monthsCombo.getElementsByTagName("div")[cal.date.getMonth()];
  Calendar.addClass(mon, "active");
  cal.activeMonth = mon;
  var s = mc.style;
  s.display = "block";
  if (cd.navtype < 0){
    s.left = cd.offsetLeft + "px";
  }
  else {
    var mcw = mc.offsetWidth;
    if (typeof mcw === "undefined"){
      mcw = 50;	// Konqueror brain-dead techniques
    }
    s.left = (cd.offsetLeft + cd.offsetWidth - mcw) + "px";
  }
  s.top = (cd.offsetTop + cd.offsetHeight) + "px";
  return true;
};

Calendar.showYearsCombo = function (fwd) {
  var cal, cd, i, s, show, Y, yc, ycw, yr;
  cal = Calendar._C;
  if (!cal) {
    return false;
  }
  cd = cal.activeDiv;
  yc = cal.yearsCombo;
  if (cal.hilitedYear) {
    Calendar.removeClass(cal.hilitedYear, "hilite");
  }
  if (cal.activeYear) {
    Calendar.removeClass(cal.activeYear, "active");
  }
  cal.activeYear = null;
  Y = cal.date.getFullYear() + (fwd ? 1 : -1);
  yr = yc.firstChild;
  show = false;
  for (i = 12; i > 0; --i) {
    if (Y >= cal.minYear && Y <= cal.maxYear) {
      yr.innerHTML = Y;
      yr.year = Y;
      yr.style.display = "block";
      show = true;
    } else {
      yr.style.display = "none";
    }
    yr = yr.nextSibling;
    Y += fwd ? cal.yearStep : -cal.yearStep;
  }
  if (show) {
    s = yc.style;
    s.display = "block";
    if (cd.navtype < 0){
      s.left = cd.offsetLeft + "px";
    }
    else {
      ycw = yc.offsetWidth;
      if (typeof ycw === "undefined"){
        ycw = 50; // Konqueror brain-dead techniques
      }
      s.left = (cd.offsetLeft + cd.offsetWidth - ycw) + "px";
    }
    s.top = (cd.offsetTop + cd.offsetHeight) + "px";
  }
  return true;
};

// event handlers

Calendar.tableMouseUp = function(ev) {
  if (!ev) { ev = window.event; }
  var cal = Calendar._C;
  if (!cal) {
    return false;
  }
  if (cal.timeout) {
    clearTimeout(cal.timeout);
  }
  var el = cal.activeDiv;
  if (!el) {
    return false;
  }
  var target = Calendar.getTargetElement(ev);
  Calendar.removeClass(el, "active");
  if (target === el || target.parentNode === el) {
    Calendar.cellClick(el, ev);
  }
  var mon = Calendar.findMonth(target);
  var date = null;
  if (mon) {
    date = new Date(cal.date);
    if (mon.month !== date.getMonth()) {
      date.setMonth(mon.month);
      cal.setDate(date);
      cal.dateClicked = false;
      cal.callHandler();
    }
  }
  else {
    var year = Calendar.findYear(target);
    if (year) {
      date = new Date(cal.date);
      if (year.year !== date.getFullYear()) {
        date.setFullYear(year.year);
        cal.setDate(date);
        cal.dateClicked = false;
        cal.callHandler();
      }
    }
  }
  Calendar.removeEvent(document, "mouseup", Calendar.tableMouseUp);
  Calendar.removeEvent(document, "mouseover", Calendar.tableMouseOver);
  Calendar.removeEvent(document, "mousemove", Calendar.tableMouseOver);
  cal._hideCombos();
  Calendar._C = null;
  return Calendar.stopEvent(ev);
};

Calendar.tableMouseOver = function (ev) {
  var cal, count, current, decrease, dx, el, i, mon, newval, pos, range, target, w, x, year;
  if (!ev) { ev = window.event; }
  cal = Calendar._C;
  if (!cal) {
    return false;
  }
  el = cal.activeDiv;
  target = Calendar.getTargetElement(ev);
  if (target === el || target.parentNode === el) {
    Calendar.addClass(el, "hilite active");
    Calendar.addClass(el.parentNode, "rowhilite");
  }
  else {
    if (typeof el.navtype === "undefined" || (el.navtype !== 50 && (el.navtype === 0 || Math.abs(el.navtype) > 2))) {
      Calendar.removeClass(el, "active");
    }
    Calendar.removeClass(el, "hilite");
    Calendar.removeClass(el.parentNode, "rowhilite");
  }
  if (el.navtype === 50 && target !== el) {
    pos = Calendar.getAbsolutePos(el);
    w = el.offsetWidth;
    x = ev.clientX;
    decrease = true;
    if (x > pos.x + w) {
      dx = x - pos.x - w;
      decrease = false;
    }
    else {
      dx = pos.x - x;
    }
    if (dx < 0) {
      dx = 0;
    }
    range = el._range;
    current = el._current;
    count = Math.floor(dx / 10) % range.length;
    for (i = range.length; --i >= 0;){
      if (range[i] === current){
        break;
      }
    }
    while (count-- > 0) {
      if (decrease) {
        if (--i < 0) {
          i = range.length - 1;
        }
      }
      else if ( ++i >= range.length ){
        i = 0;
      }
    }
    newval = range[i];
    el.innerHTML = newval;

    cal.onUpdateTime();
  }
  mon = Calendar.findMonth(target);
  if (mon) {
    if (mon.month !== cal.date.getMonth()) {
      if (cal.hilitedMonth) {
        Calendar.removeClass(cal.hilitedMonth, "hilite");
      }
      Calendar.addClass(mon, "hilite");
      cal.hilitedMonth = mon;
    } else if (cal.hilitedMonth) {
      Calendar.removeClass(cal.hilitedMonth, "hilite");
    }
  } else {
    if (cal.hilitedMonth) {
      Calendar.removeClass(cal.hilitedMonth, "hilite");
    }
    year = Calendar.findYear(target);
    if (year) {
      if (year.year !== cal.date.getFullYear()) {
        if (cal.hilitedYear) {
          Calendar.removeClass(cal.hilitedYear, "hilite");
        }
        Calendar.addClass(year, "hilite");
        cal.hilitedYear = year;
      } else if (cal.hilitedYear) {
        Calendar.removeClass(cal.hilitedYear, "hilite");
      }
    } else if (cal.hilitedYear) {
      Calendar.removeClass(cal.hilitedYear, "hilite");
    }
  }
  return Calendar.stopEvent(ev);
};

Calendar.tableMouseDown = function (ev) {
  if (Calendar.getTargetElement(ev) === Calendar.getElement(ev)) {
    return Calendar.stopEvent(ev);
  }
  return true;
};

Calendar.calDragIt = function (ev) {
  var cal = Calendar._C;
  if (!(cal && cal.dragging)) {
    return false;
  }
  var posX;
  var posY;
  if (Calendar.is_ie) {
    posY = window.event.clientY + document.body.scrollTop;
    posX = window.event.clientX + document.body.scrollLeft;
  } else {
    posX = ev.pageX;
    posY = ev.pageY;
  }
  cal.hideShowCovered();
  var st = cal.element.style;
  st.left = (posX - cal.xOffs) + "px";
  st.top = (posY - cal.yOffs) + "px";
  return Calendar.stopEvent(ev);
};

Calendar.calDragEnd = function (ev) {
  var cal = Calendar._C;
  if (!cal) {
    return false;
  }
  cal.dragging = false;
  Calendar.removeEvent(document, "mousemove", Calendar.calDragIt);
  Calendar.removeEvent(document, "mouseup", Calendar.calDragEnd);
  Calendar.tableMouseUp(ev);
  cal.hideShowCovered();
  return true;
};

Calendar.dayMouseDown = function(ev) {
  var el = Calendar.getElement(ev);
  if (el.disabled) {
    return false;
  }
  var cal = el.calendar;
  cal.activeDiv = el;
  Calendar._C = cal;
  if (el.navtype !== 300) {
    if (el.navtype === 50) {
      el._current = el.innerHTML;
      Calendar.addEvent(document, "mousemove", Calendar.tableMouseOver);
    }
    else {
      Calendar.addEvent(document, Calendar.is_ie5 ? "mousemove" : "mouseover", Calendar.tableMouseOver);
    }
    Calendar.addClass(el, "hilite active");
    Calendar.addEvent(document, "mouseup", Calendar.tableMouseUp);
  }
  else if (cal.isPopup) {
    cal._dragStart(ev);
  }
  if (el.navtype === -1 || el.navtype === 1) {
    if (cal.timeout) {
      clearTimeout(cal.timeout);
    }
    cal.timeout = setTimeout(Calendar.showMonthsCombo, 250);
  }
  else if (el.navtype === -2 || el.navtype === 2) {
    if (cal.timeout) {
      clearTimeout(cal.timeout);
    }
    cal.timeout = setTimeout((el.navtype > 0) ? "Calendar.showYearsCombo(true)" : "Calendar.showYearsCombo(false)", 250);
  }
  else {
    cal.timeout = null;
  }
  return Calendar.stopEvent(ev);
};

Calendar.dayMouseDblClick = function(ev) {
  Calendar.cellClick(Calendar.getElement(ev), ev || window.event);
  if (Calendar.is_ie) {
    document.selection.empty();
  }
};

Calendar.dayMouseOver = function(ev) {
  var el = Calendar.getElement(ev);
  if (Calendar.isRelated(el, ev) || Calendar._C || el.disabled) {
    return false;
  }
  if (el.ttip) {
    if (el.ttip.substr(0, 1) === "_") {
      el.ttip = el.caldate.print(el.calendar.ttDateFormat) + el.ttip.substr(1);
    }
    el.calendar.tooltips.innerHTML = el.ttip;
  }
  if (el.navtype !== 300) {
    Calendar.addClass(el, "hilite");
    if (el.caldate) {
      Calendar.addClass(el.parentNode, "rowhilite");
    }
  }
  return Calendar.stopEvent(ev);
};

Calendar.dayMouseOut = function(ev) {
  var el = Calendar.getElement(ev);
  if (Calendar.isRelated(el, ev) || (typeof Calendar._C!=='undefined' && Calendar._C) || el.disabled) {
    return false;
  }
  Calendar.removeClass(el, "hilite");
  if (el.caldate) {
    Calendar.removeClass(el.parentNode, "rowhilite");
  }
  if (el.calendar) {
    el.calendar.tooltips.innerHTML = Calendar._TT.SEL_DATE;
  }
  return Calendar.stopEvent(ev);
};

/**
 *  A generic "click" handler :) handles all types of buttons defined in this
 *  calendar.
 */
Calendar.cellClick = function(el, ev) {
  var cal, closing, current, date, day, i, max, mon, newdate, newval, other_month;
  var popup_URL, range, setMonth, text, year;
  cal = el.calendar;
  closing = false;
  newdate = false;
  date = null;
  if (typeof el.navtype === "undefined") {
    if (cal.currentDateEl) {
      Calendar.removeClass(cal.currentDateEl, "selected");
      Calendar.addClass(el, "selected");
      closing = (cal.currentDateEl === el);
      if (!closing) {
        cal.currentDateEl = el;
      }
    }
    cal.date.setDateOnly(el.caldate);
    date = cal.date;
    other_month = !(cal.dateClicked = !el.otherMonth);
    if (!other_month && !cal.currentDateEl) {
      cal._toggleMultipleDate(new Date(date));
    }
    else {
      newdate = !el.disabled;
    }
    // a date was clicked
    if (other_month) {
      cal._init(cal.firstDayOfWeek, date);
    }
  }
  else {
    if (el.navtype === 200) {
      Calendar.removeClass(el, "hilite");
      cal.callCloseHandler();
      return;
    }
    if (el.navtype === 500) {
      popup_help('_help_user_calendar');
      Calendar.removeClass(el, "hilite");
      cal.callCloseHandler();
      return;
    }
    if (el.navtype === 600) {
      if (cal.link_enlarge_popup==='1') {
        popup_URL = base_url+cal.link_enlarge;
        popup_calendar_large(popup_URL);
      }
      else {
        window.location = base_url+cal.link_enlarge+"?YYYY="+geid_val('YYYY')+"&MM="+geid_val("MM")+"&DD="+geid_val('DD');
      }
      Calendar.removeClass(el, "hilite");
      cal.callCloseHandler();
      return;
    }

    date = new Date(cal.date);
    if (el.navtype === 0){
      date.setDateOnly(new Date()); // TODAY
    }
    // unless "today" was clicked, we assume no date was clicked so
    // the selected handler will know not to close the calenar when
    // in single-click mode.
    // cal.dateClicked = (el.navtype === 0);
    cal.dateClicked = false;
    year = date.getFullYear();
    mon = date.getMonth();

    setMonth = function (m) {
      day = date.getDate();
      max = date.getMonthDays(m);
      if (day > max) {
        date.setDate(max);
      }
      date.setMonth(m);
    };
    switch (el.navtype) {
      case 400:
        Calendar.removeClass(el, "hilite");
        text = Calendar._TT.ABOUT;
        if (typeof text !== "undefined") {
          text += cal.showsTime ? Calendar._TT.ABOUT_TIME : "";
        }
        else {
        // FIXME: this should be removed as soon as lang files get updated!
          text =
            "Help and about box text is not translated into this language.\n" +
            "If you know this language and you feel generous please update\n" +
            "the corresponding file in \"lang\" subdir to match calendar-en.js\n" +
            "and send it back to <mihai_bazon@yahoo.com> to get it into the distribution  ;-)\n\n" +
            "Thank you!\n" +
            "http://dynarch.com/mishoo/calendar.epl\n";
        }
        alert(text);
        return;
      case -2:
        if (year > cal.minYear) {
          date.setFullYear(year - 1);
        }
      break;
      case -1:
        if (mon > 0) {
          setMonth(mon - 1);
        }
        else if (year-- > cal.minYear) {
          date.setFullYear(year);
          setMonth(11);
        }
      break;
      case 1:
        if (mon < 11) {
          setMonth(mon + 1);
        }
        else if (year < cal.maxYear) {
          date.setFullYear(year + 1);
          setMonth(0);
        }
      break;
      case 2:
        if (year < cal.maxYear) {
          date.setFullYear(year + 1);
        }
      break;
      case 100:
        cal.setFirstDayOfWeek(el.fdow);
        return;
      case 50:
        range = el._range;
        current = el.innerHTML;
        for (i = range.length; --i >= 0;) {
          if (range[i] === current) {
            break;
          }
        }
        if (ev && ev.shiftKey) {
          if (--i < 0) {
            i = range.length - 1;
          }
        }
        else if ( ++i >= range.length ){
          i = 0;
        }
        newval = range[i];
        el.innerHTML = newval;
        cal.onUpdateTime();
        return;
      case 0:
        // TODAY will bring us here
        cal.dateClicked = true;
        if ((typeof cal.getDateStatus === "function") && cal.getDateStatus(date, date.getFullYear(), date.getMonth(), date.getDate())) {
          return;
        }
      break;
    }
    if (!date.equalsTo(cal.date)) {
      cal.setDate(date);
      newdate = true;
    }
    else if (el.navtype === 0){
      newdate = closing = true;
    }
  }
  if (ev && newdate) {
    cal.callHandler();
  }
  if (ev && closing) {
    Calendar.removeClass(el, "hilite");
    cal.callCloseHandler();
  }
};

// END: CALENDAR STATIC FUNCTIONS

// BEGIN: CALENDAR OBJECT FUNCTIONS

/**
 *  This function creates the calendar inside the given parent.  If _par is
 *  null than it creates a popup calendar inside the BODY element.  If _par is
 *  an element, be it BODY, then it creates a non-popup calendar (still
 *  hidden).  Some properties need to be set before calling this function.
 */
Calendar.prototype.create = function (_par) {
  var _close_btn, _help_btn, _link_btn;
  var cal, cell, div, hh, i, j, parent, row, table, tbody, thead, title_length;
  parent = null;
  if (! _par) {
    // default parent is the document body, in which case we create
    // a popup calendar.
    parent = document.getElementsByTagName("body")[0];
    this.isPopup = true;
  } else {
    parent = _par;
    this.isPopup = false;
  }
  this.date = this.dateStr ? new Date(this.dateStr) : new Date();
  table = Calendar.createElement("table");
  this.table = table;
  table.cellSpacing = 0;
  table.cellPadding = 0;
  table.calendar = this;
  Calendar.addEvent(table, "mousedown", Calendar.tableMouseDown);
  div = Calendar.createElement("div");
  this.element = div;
  div.className = "calendar_mini cal_table";
  if (this.isPopup) {
    div.style.position = "absolute";
    div.style.width = "200px";
    div.style.display = "none";
  }
  div.appendChild(table);
  thead = Calendar.createElement("thead", table);
  cell = null;
  row = null;
  cal = this;
  hh = function (text, cs, navtype) {
    cell = Calendar.createElement("td", row);
    cell.colSpan = cs;
    cell.className = "button";
    if (navtype !== 0 && Math.abs(navtype) <= 2) {
      cell.className += " cal_nav";
    }
    Calendar._add_evs(cell);
    cell.calendar = cal;
    cell.navtype = navtype;
    cell.innerHTML = "<div unselectable='on'>" + text + "</div>";
    return cell;
  };
  row = Calendar.createElement("tr", thead);
  title_length = 5;
  if (this.isPopup) {
    --title_length;
  }
  if (this.weekNumbers) {
    ++title_length;
  }
  if (this.link_enlarge) {
    _link_btn = hh("", 1, 600);
    _link_btn.ttip = "Enlarge or print calendar";
    _link_btn.title = "Enlarge";
    _link_btn.className = "title cal_head cal_enlarge";
  }
  else {
    _link_btn = hh("", 1, 300);
    _link_btn.className = "title cal_head";
  }
  this.title = hh("", title_length, 300);
  this.title.className = "title cal_head";
  if (this.isPopup) {
    this.title.ttip = Calendar._TT.DRAG_TO_MOVE;
    this.title.style.cursor = "move";
    _close_btn = hh("&#x00d7;", 1, 200);
    _close_btn.ttip = Calendar._TT.CLOSE;
    _close_btn.className = "title cal_head";
  }
  if (this.link_help==='1') {
    _help_btn = hh("", 1, 500);
    _help_btn.ttip = "View Help for calendar";
    _help_btn.title = "Help";
    _help_btn.className = "title cal_head cal_help";
  }
  else {
    _help_btn = hh("", 1, 300);
    _help_btn.className = "title cal_head";
  }
  row = Calendar.createElement("tr", thead);
  row.className = "headrow";
  this._nav_py = hh("&#x00ab;", 1, -2);
  this._nav_py.title = this._nav_py.ttip = Calendar._TT.PREV_YEAR;
  this._nav_pm = hh("&#x2039;", 1, -1);
  this._nav_pm.title = this._nav_pm.ttip = Calendar._TT.PREV_MONTH;
  this._nav_now = hh(Calendar._TT.TODAY, this.weekNumbers ? 4 : 3, 0);
  this._nav_now.title = this._nav_now.ttip = Calendar._TT.GO_TODAY;
  this._nav_nm = hh("&#x203a;", 1, 1);
  this._nav_nm.title = this._nav_nm.ttip = Calendar._TT.NEXT_MONTH;
  this._nav_ny = hh("&#x00bb;", 1, 2);
  this._nav_ny.title = this._nav_ny.ttip = Calendar._TT.NEXT_YEAR;
  // day names
  row = Calendar.createElement("tr", thead);
  if (this.weekNumbers) {
    cell = Calendar.createElement("td", row);
    cell.className = "name wn";
    cell.innerHTML = Calendar._TT.WK;
  }
  for (i = 7; i > 0; --i) {
    cell = Calendar.createElement("td", row);
    if (!i) {
      cell.navtype = 100;
      cell.calendar = this;
      Calendar._add_evs(cell);
    }
  }
  this.firstdayname = (this.weekNumbers) ? row.firstChild.nextSibling : row.firstChild;
  this._displayWeekdays();
  tbody = Calendar.createElement("tbody", table);
  this.tbody = tbody;
  for (i=6; i>0; --i) {
    row = Calendar.createElement("tr", tbody);
    if (this.weekNumbers) {
      cell = Calendar.createElement("td", row);
    }
    for (j = 7; j > 0; --j) {
      cell = Calendar.createElement("td", row);
      cell.calendar = this;
      Calendar._add_evs(cell);
    }
  }
  if (this.showsTime) {
    row = Calendar.createElement("tr", tbody);
    row.className = "time";
    cell = Calendar.createElement("td", row);
    cell.className = "time";
    cell.colSpan = 2;
    cell.innerHTML = Calendar._TT.TIME || "&nbsp;";
    cell = Calendar.createElement("td", row);
    cell.className = "time";
    cell.colSpan = this.weekNumbers ? 4 : 3;
    (function(){
      function makeTimePart(className, init, range_start, range_end) {
        var i, part, txt;
        part = Calendar.createElement("span", cell);
        part.className = className;
        part.innerHTML = init;
        part.calendar = cal;
        part.ttip = Calendar._TT.TIME_PART;
        part.navtype = 50;
        part._range = [];
        if (typeof range_start !== "number") {
          part._range = range_start;
        }
        else {
          for (i = range_start; i <= range_end; ++i){
            if (i < 10 && range_end >= 10) {
              txt = '0' + i;
            }
            else {
              txt = '' + i;
            }
            part._range[part._range.length] = txt;
          }
        }
        Calendar._add_evs(part);
        return part;
      }
      var hrs = cal.date.getHours();
      var mins = cal.date.getMinutes();
      var t12 = !cal.time24;
      var pm = (hrs > 12);
      if (t12 && pm) {
        hrs -= 12;
      }
      var H = makeTimePart("hour", hrs, t12 ? 1 : 0, t12 ? 12 : 23);
      var span = Calendar.createElement("span", cell);
      span.innerHTML = ":";
      span.className = "colon";
      var M = makeTimePart("minute", mins, 0, 59);
      var AP = null;
      cell = Calendar.createElement("td", row);
      cell.className = "time";
      cell.colSpan = 2;
      if (t12) {
        AP = makeTimePart("ampm", pm ? "pm" : "am", ["am", "pm"]);
      }
      else {
        cell.innerHTML = "&nbsp;";
      }

      cal.onSetTime = function() {
        var pm, hrs = this.date.getHours(), mins = this.date.getMinutes();
        if (t12) {
          pm = (hrs >= 12);
          if (pm) {
            hrs -= 12;
          }
          if (hrs === 0) {
            hrs = 12;
          }
          AP.innerHTML = (pm ? "pm" : "am");
        }
        H.innerHTML = ((hrs < 10) ? ("0" + hrs) : hrs);
        M.innerHTML = ((mins < 10) ? ("0" + mins) : mins);
      };

      cal.onUpdateTime = function() {
        var date = this.date;
        var h = parseInt(H.innerHTML, 10);
        if (t12) {
          if (/pm/i.test(AP.innerHTML) && h < 12) {
            h += 12;
          }
          else if (/am/i.test(AP.innerHTML) && h === 12) {
            h = 0;
          }
        }
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        date.setHours(h);
        date.setMinutes(parseInt(M.innerHTML, 10));
        date.setFullYear(y);
        date.setMonth(m);
        date.setDate(d);
        this.dateClicked = false;
        this.callHandler();
      };
    })();
  }
  else {
    this.onSetTime = this.onUpdateTime = function() {};
  }
  var tfoot = Calendar.createElement("tfoot", table);
  row = Calendar.createElement("tr", tfoot);
  row.className = "footrow";
  cell = hh(Calendar._TT.SEL_DATE, this.weekNumbers ? 8 : 7, 300);
  cell.className = "ttip";
  if (this.isPopup) {
    cell.ttip = Calendar._TT.DRAG_TO_MOVE;
    cell.style.cursor = "move";
  }
  this.tooltips = cell;
  div = Calendar.createElement("div", this.element);
  this.monthsCombo = div;
  div.className = "combo";
  for (i = 0; i < Calendar._MN.length; ++i) {
    var mn = Calendar.createElement("div");
    mn.className = (Calendar.is_ie ? "label-IEfix" : "label");
    mn.month = i;
    mn.innerHTML = Calendar._SMN[i];
    div.appendChild(mn);
  }
  div = Calendar.createElement("div", this.element);
  this.yearsCombo = div;
  div.className = "combo";
  for (i = 12; i > 0; --i) {
    var yr = Calendar.createElement("div");
    yr.className = (Calendar.is_ie ? "label-IEfix" : "label");
    div.appendChild(yr);
  }
  this._init(this.firstDayOfWeek, this.date);
  parent.appendChild(this.element);
};

/** keyboard navigation, only for popup calendars */
Calendar._keyEvent =
  function(ev) {
    var nextMonth, prevMonth, setVars;
    var cal = window._dynarch_popupCalendar;
    if (!cal || cal.multiple) {
      return false;
    }
    if (Calendar.is_ie) {
      ev = window.event;
    }
    var act = (Calendar.is_ie || ev.type === "keypress"), K = ev.keyCode;
    if (ev.ctrlKey) {
      switch (K) {
        case 37: // KEY left
          if (act) { Calendar.cellClick(cal._nav_pm); }
        break;
        case 38: // KEY up
          if (act) { Calendar.cellClick(cal._nav_py); }
        break;
        case 39: // KEY right
          if (act) { Calendar.cellClick(cal._nav_nm); }
        break;
        case 40: // KEY down
          if (act) { Calendar.cellClick(cal._nav_ny); }
        break;
        default:
          return false;
        }
    }
    else switch (K) {
      case 32: // KEY space (now)
        Calendar.cellClick(cal._nav_now);
      break;
      case 27: // KEY esc
        if (act) { cal.callCloseHandler(); }
      break;
      case 37: // KEY left
      case 38: // KEY up
      case 39: // KEY right
      case 40: // KEY down
        if (act) {
          var prev, x, y, ne, el, step;
          prev = K === 37 || K === 38;
          step = (K === 37 || K === 39) ? 1 : 7;
          setVars = function() {
            el = cal.currentDateEl;
            var p = el.pos;
            x = p & 15;
            y = p >> 4;
            ne = cal.ar_days[y][x];
          };
          setVars();
          prevMonth = function() {
            var date = new Date(cal.date);
            date.setDate(date.getDate() - step);
            cal.setDate(date);
          };
          nextMonth = function() {
            var date = new Date(cal.date);
            date.setDate(date.getDate() + step);
            cal.setDate(date);
          };
          while(true){
            switch (K){
              case 37: // KEY left
                if (--x >= 0) {
                  ne = cal.ar_days[y][x];
                }
                else {
                  x = 6;
                  K = 38;
                  continue;
                }
              break;
              case 38: // KEY up
                if (--y >= 0) {
                  ne = cal.ar_days[y][x];
                }
              else {
                prevMonth();
                setVars();
              }
            break;
            case 39: // KEY right
              if (++x < 7){
                ne = cal.ar_days[y][x];
              }
              else {
                x = 0;
                K = 40;
                continue;
              }
            break;
            case 40: // KEY down
              if (++y < cal.ar_days.length) {
                ne = cal.ar_days[y][x];
              }
              else {
                nextMonth();
                setVars();
              }
            break;
          }
        break;
      }
      if (ne) {
        if (!ne.disabled) {
          Calendar.cellClick(ne);
        }
        else if (prev) {
          prevMonth();
        }
        else {
          nextMonth();
        }
      }
    }
    break;
    case 13: // KEY enter
      if (act) {
        Calendar.cellClick(cal.currentDateEl, ev);
      }
    break;
    default:
      return false;
  }
  return Calendar.stopEvent(ev);
};

/**
 *  (RE)Initializes the calendar to the given date and firstDayOfWeek
 */
Calendar.prototype._init = function (firstDayOfWeek, date) {
  var ar_days, cell, current_month, dates, day1, dpos, hasdays, i, iday, j, mday, month;
  var row, status, today, toolTip, TD, TM, TY, wday, weekend, year;
  today = new Date();
  TY = today.getFullYear();
  TM = today.getMonth();
  TD = today.getDate();
  this.table.style.visibility = "hidden";
  year = date.getFullYear();
  if (year < this.minYear) {
    year = this.minYear;
    date.setFullYear(year);
  }
  else if (year > this.maxYear) {
    year = this.maxYear;
    date.setFullYear(year);
  }
  this.firstDayOfWeek = firstDayOfWeek;
  this.date = new Date(date);
  month = date.getMonth();
  mday = date.getDate();
  // calendar voodoo for computing the first day that would actually be
  // displayed in the calendar, even if it's from the previous month.
  // WARNING: this is magic. ;-)
  date.setDate(1);
  day1 = (date.getDay() - this.firstDayOfWeek) % 7;
// Changed this line to ensure some datesfrom last month are always shown
//if (day1 < 0){
  if (day1 < 1){
    day1 += 7;
  }
  date.setDate(-day1);
  date.setDate(date.getDate() + 1);
  row = this.tbody.firstChild;
  ar_days = [];
  this.ar_days = [];
  weekend = Calendar._TT.WEEKEND;
  dates = this.multiple ? (this.datesCells = {}) : null;
  for (i = 0; i < 6; ++i, row = row.nextSibling) {
    cell = row.firstChild;
    if (this.weekNumbers) {
      cell.className = "day wn";
      cell.innerHTML = date.getWeekNumber();
      cell = cell.nextSibling;
    }
    row.className = "daysrow";
    hasdays = false;
    dpos = [];
    ar_days[i] = [];
    for (j = 0; j < 7; ++j, cell = cell.nextSibling, date.setDate(iday + 1)) {
      iday = date.getDate();
      wday = date.getDay();
      cell.className = "cal_current";
      cell.pos = i << 4 | j;
      dpos[j] = cell;
      current_month = (date.getMonth() === month);
      if (!current_month) {
        if (this.showsOtherMonths) {
          cell.className += " cal_then";
          cell.otherMonth = true;
        } else {
          cell.className = "emptycell";
          cell.innerHTML = "&nbsp;";
          cell.disabled = true;
          continue;
        }
      } else {
        cell.otherMonth = false;
        hasdays = true;
      }
      cell.disabled = false;
      cell.innerHTML = this.getDateText ? this.getDateText(date, iday) : iday;
      if (dates) {
        dates[date.print("%Y%m%d")] = cell;
      }
      if (this.getDateStatus) {
        status = this.getDateStatus(date, year, month, iday);
        if (this.getDateToolTip) {
          toolTip = this.getDateToolTip(date, year, month, iday);
          if (toolTip) {
            cell.title = toolTip;
          }
        }
        if (status === true) {
          cell.className += " disabled";
          cell.disabled = true;
        }
         else {
          if (/disabled/i.test(status)) {
            cell.disabled = true;
          }
          cell.className += " " + status;
        }
      }
      if (!cell.disabled) {
        cell.caldate = new Date(date);
        cell.ttip = "_";
        if (!this.multiple && current_month && iday === mday && this.hiliteToday) {
          cell.className += " selected";
          this.currentDateEl = cell;
        }
        if (date.getFullYear() === TY && date.getMonth() === TM && iday === TD) {
          cell.className += " cal_today";
          cell.ttip += Calendar._TT.PART_TODAY;
        }
        if (weekend.indexOf(wday.toString()) !== -1) {
          cell.className += cell.otherMonth ? " cal_then_we" : " cal_current_we";
        }
      }
    }
    if (!(hasdays || this.showsOtherMonths)) {
      row.className = "emptyrow";
    }
  }
  this.title.innerHTML = Calendar._MN[month] + ", " + year;
  this.onSetTime();
  this.table.style.visibility = "visible";
  this._initMultipleDates();
  // PROFILE
  // this.tooltips.innerHTML = "Generated in " + ((new Date()) - today) + " ms";
};

Calendar.prototype._initMultipleDates = function() {
  var cell, d, i;
  if (this.multiple) {
    for (i in this.multiple) {
      if (this.multiple.hasOwnProperty(i)){
        cell = this.datesCells[i];
        d = this.multiple[i];
        if (!d) {
          continue;
        }
        if (cell) {
          cell.className += " selected";
        }
      }
    }
  }
};

Calendar.prototype._toggleMultipleDate = function(date) {
  var cell, d, ds;
  if (this.multiple) {
    ds = date.print("%Y%m%d");
    cell = this.datesCells[ds];
    if (cell) {
      d = this.multiple[ds];
      if (!d) {
        Calendar.addClass(cell, "selected");
        this.multiple[ds] = date;
      }
      else {
        Calendar.removeClass(cell, "selected");
        delete this.multiple[ds];
      }
    }
  }
};

Calendar.prototype.setDateToolTipHandler = function (unaryFunction) {
  this.getDateToolTip = unaryFunction;
};
Calendar.prototype.setLinkEnlarge = function (link_enlarge) {
  this.link_enlarge = link_enlarge;
};
Calendar.prototype.setLinkEnlargePopup = function (link_enlarge_popup) {
  this.link_enlarge_popup = link_enlarge_popup;
};
Calendar.prototype.setLinkHelp = function (link_help) {
  this.link_help = link_help;
};

/**
 *  Calls _init function above for going to a certain date (but only if the
 *  date is different than the currently selected one).
 */
Calendar.prototype.setDate = function (date) {
  if (!date.equalsTo(this.date)) {
    this._init(this.firstDayOfWeek, date);
  }
};

/**
 *  Refreshes the calendar.  Useful if the "disabledHandler" function is
 *  dynamic, meaning that the list of disabled date can change at runtime.
 *  Just * call this function if you think that the list of disabled dates
 *  should * change.
 */
Calendar.prototype.refresh = function () {
  this._init(this.firstDayOfWeek, this.date);
};

/** Modifies the "firstDayOfWeek" parameter (pass 0 for Synday, 1 for Monday, etc.). */
Calendar.prototype.setFirstDayOfWeek = function (firstDayOfWeek) {
  this._init(firstDayOfWeek, this.date);
  this._displayWeekdays();
};

/**
 *  Allows customization of what dates are enabled.  The "unaryFunction"
 *  parameter must be a function object that receives the date (as a JS Date
 *  object) and returns a boolean value.  If the returned value is true then
 *  the passed date will be marked as disabled.
 */
Calendar.prototype.setDateStatusHandler = Calendar.prototype.setDisabledHandler = function (unaryFunction) {
  this.getDateStatus = unaryFunction;
};

/** Customization of allowed year range for the calendar. */
Calendar.prototype.setRange = function (a, z) {
  this.minYear = a;
  this.maxYear = z;
};

/** Calls the first user handler (selectedHandler). */
Calendar.prototype.callHandler = function () {
  if (this.onSelected) {
    this.onSelected(this, this.date.print(this.dateFormat));
  }
};

/** Calls the second user handler (closeHandler). */
Calendar.prototype.callCloseHandler = function () {
  if (this.onClose) {
    this.onClose(this);
  }
  this.hideShowCovered();
};

/** Removes the calendar object from the DOM tree and destroys it. */
Calendar.prototype.destroy = function () {
  var el = this.element.parentNode;
  el.removeChild(this.element);
  Calendar._C = null;
  window._dynarch_popupCalendar = null;
};

/**
 *  Moves the calendar element to a different section in the DOM tree (changes
 *  its parent).
 */
Calendar.prototype.reparent = function (new_parent) {
  var el = this.element;
  el.parentNode.removeChild(el);
  new_parent.appendChild(el);
};

// This gets called when the user presses a mouse button anywhere in the
// document, if the calendar is shown.  If the click was outside the open
// calendar this function closes it.
Calendar._checkCalendar = function(ev) {
  var calendar = window._dynarch_popupCalendar;
  if (!calendar) {
    return false;
  }
  var el = Calendar.is_ie ? Calendar.getElement(ev) : Calendar.getTargetElement(ev);
  for (; el !== null && el !== calendar.element; el = el.parentNode){
  }
  if (el === null){    // calls closeHandler which should hide the calendar.
    window._dynarch_popupCalendar.callCloseHandler();
    return Calendar.stopEvent(ev);
  }
  return true;
};

/** Shows the calendar. */
Calendar.prototype.show = function () {
  var cell, cells, i, j, row, rows;
  rows = this.table.getElementsByTagName("tr");
  for (i = rows.length; i > 0;) {
    row = rows[--i];
    Calendar.removeClass(row, "rowhilite");
    cells = row.getElementsByTagName("td");
    for (j = cells.length; j > 0;) {
      cell = cells[--j];
      Calendar.removeClass(cell, "hilite");
      Calendar.removeClass(cell, "active");
    }
  }
  this.element.style.display = "block";
  this.hidden = false;
  if (this.isPopup) {
    window._dynarch_popupCalendar = this;
    Calendar.addEvent(document, "keydown", Calendar._keyEvent);
    Calendar.addEvent(document, "keypress", Calendar._keyEvent);
    Calendar.addEvent(document, "mousedown", Calendar._checkCalendar);
  }
  this.hideShowCovered();
};

/**
 *  Hides the calendar.  Also removes any "hilite" from the class of any TD
 *  element.
 */
Calendar.prototype.hide = function () {
  if (this.isPopup) {
    Calendar.removeEvent(document, "keydown", Calendar._keyEvent);
    Calendar.removeEvent(document, "keypress", Calendar._keyEvent);
    Calendar.removeEvent(document, "mousedown", Calendar._checkCalendar);
  }
  this.element.style.display = "none";
  this.hidden = true;
  this.hideShowCovered();
};

/**
 *  Shows the calendar at a given absolute position (beware that, depending on
 *  the calendar element style -- position property -- this might be relative
 *  to the parent's containing rectangle).
 */
Calendar.prototype.showAt = function (x, y) {
  var s = this.element.style;
  s.left = x + "px";
  s.top = y + "px";
  this.show();
};

/** Shows the calendar near a given element. */
Calendar.prototype.showAtElement = function (el, opts) {
  var self = this;
  var p = Calendar.getAbsolutePos(el);
  if (!opts || typeof opts !== "string") {
    this.showAt(p.x, p.y + el.offsetHeight);
    return true;
  }
  function fixPosition(box) {
    if (box.x < 0) {
      box.x = 0;
    }
    if (box.y < 0) {
      box.y = 0;
    }
    var cp = document.createElement("div");
    var s = cp.style;
    s.position = "absolute";
    s.right = s.bottom = s.width = s.height = "0px";
    document.body.appendChild(cp);
    var br = Calendar.getAbsolutePos(cp);
    document.body.removeChild(cp);
    if (Calendar.is_ie) {
      br.y += document.body.scrollTop;
      br.x += document.body.scrollLeft;
    }
    else {
      br.y += window.scrollY;
      br.x += window.scrollX;
    }
    var tmp = box.x + box.width - br.x;
    if (tmp > 0) {
      box.x -= tmp;
    }
    tmp = box.y + box.height - br.y;
    if (tmp > 0) {
      box.y -= tmp;
    }
  }
  this.element.style.display = "block";
  Calendar.continuation_for_the_khtml_browser = function() {
    var w = self.element.offsetWidth;
    var h = self.element.offsetHeight;
    self.element.style.display = "none";
    var valign = opts.substr(0, 1);
    var halign = "l";
    if (opts.length > 1) {
      halign = opts.substr(1, 1);
    }
    // vertical alignment
    switch (valign) {
      case "T": p.y -= h; break;
      case "B": p.y += el.offsetHeight; break;
      case "C": p.y += (el.offsetHeight - h) / 2; break;
      case "t": p.y += el.offsetHeight - h; break;
      case "b": break; // already there
    }
    // horizontal alignment
    switch (halign) {
      case "L": p.x -= w; break;
      case "R": p.x += el.offsetWidth; break;
      case "C": p.x += (el.offsetWidth - w) / 2; break;
      case "l": p.x += el.offsetWidth - w; break;
      case "r": break; // already there
    }
    p.width = w;
    p.height = h + 40;
    self.monthsCombo.style.display = "none";
    fixPosition(p);
    self.showAt(p.x, p.y);
  };
  if (Calendar.is_khtml) {
    setTimeout(Calendar.continuation_for_the_khtml_browser, 10);
  }
  else {
    Calendar.continuation_for_the_khtml_browser();
  }
  return false;
};

/** Customizes the date format. */
Calendar.prototype.setDateFormat = function (str) {
  this.dateFormat = str;
};

/** Customizes the tooltip date format. */
Calendar.prototype.setTtDateFormat = function (str) {
  this.ttDateFormat = str;
};

/**
 *  Tries to identify the date represented in a string.  If successful it also
 *  calls this.setDate which moves the calendar to the given date.
 */
Calendar.prototype.parseDate = function(str, fmt) {
  if (!fmt) {
    fmt = this.dateFormat;
  }
  this.setDate(Date.parseDate(str, fmt));
};

Calendar.prototype.hideShowCovered = function () {
  if (!Calendar.is_ie && !Calendar.is_opera) {
    return;
  }
  if (!isIE_lt8){
    return;
  }
  function getVisib(obj){
    var value;
    value = obj.style.visibility;
    if (!value) {
      if (document.defaultView && typeof (document.defaultView.getComputedStyle) === "function") { // Gecko, W3C
        if (!Calendar.is_khtml) {
          value = document.defaultView.
            getComputedStyle(obj, "").getPropertyValue("visibility");
        }
        else {
          value = '';
        }
      }
      else if (obj.currentStyle) { // IE
        value = obj.currentStyle.visibility;
      }
      else {
        value = '';
      }
    }
    return value;
  }
  var ar, cc, CX1, CX2, CY1, CY2, el, EX1, EX2, EY1, EY2, i, k, p, tags;
  tags = ["applet", "iframe", "select"];
  el = this.element;
  p = Calendar.getAbsolutePos(el);
  EX1 = p.x;
  EX2 = el.offsetWidth + EX1;
  EY1 = p.y;
  EY2 = el.offsetHeight + EY1;
  for (k = tags.length; k > 0; ) {
    ar = document.getElementsByTagName(tags[--k]);
    cc = null;
    for (i = ar.length; i > 0;) {
      cc = ar[--i];
      p = Calendar.getAbsolutePos(cc);
      CX1 = p.x;
      CX2 = cc.offsetWidth + CX1;
      CY1 = p.y;
      CY2 = cc.offsetHeight + CY1;
      if (this.hidden || (CX1 > EX2) || (CX2 < EX1) || (CY1 > EY2) || (CY2 < EY1)) {
        if (!cc.__msh_save_visibility) {
          cc.__msh_save_visibility = getVisib(cc);
        }
        cc.style.visibility = cc.__msh_save_visibility;
      }
      else {
        if (!cc.__msh_save_visibility) {
          cc.__msh_save_visibility = getVisib(cc);
        }
        cc.style.visibility = "hidden";
      }
    }
  }
};

/** Internal function; it displays the bar with the names of the weekday. */
Calendar.prototype._displayWeekdays = function () {
  var cell, fdow, i, realday, weekend;
  fdow = this.firstDayOfWeek;
  cell = this.firstdayname;
  weekend = Calendar._TT.WEEKEND;
  for (i = 0; i < 7; ++i) {
    cell.className = "cal_days";
    if (i===6) { cell.className += " cal_days_s"; }
    realday = (i + fdow) % 7;
    if (i) {
      cell.ttip = Calendar._TT.DAY_FIRST.replace("%s", Calendar._DN[realday]);
      cell.navtype = 100;
      cell.calendar = this;
      cell.fdow = realday;
//      Calendar._add_evs(cell); // Changed by M Francis - removes ability to change start day for each week
    }
    if (weekend.indexOf(realday.toString()) !== -1) {
      Calendar.addClass(cell, "weekend");
    }
    cell.innerHTML = Calendar._SDN[(i + fdow) % 7].substr(0,2);
    cell = cell.nextSibling;
  }
};

/** Internal function.  Hides all combo boxes that might be displayed. */
Calendar.prototype._hideCombos = function () {
  this.monthsCombo.style.display = "none";
  this.yearsCombo.style.display = "none";
};

/** Internal function.  Starts dragging the element. */
Calendar.prototype._dragStart = function (ev) {
  var posX, posY, st;
  if (this.dragging) {
    return;
  }
  this.dragging = true;
  if (Calendar.is_ie) {
    posY = window.event.clientY + document.body.scrollTop;
    posX = window.event.clientX + document.body.scrollLeft;
  }
  else {
    posY = ev.clientY + window.scrollY;
    posX = ev.clientX + window.scrollX;
  }
  st = this.element.style;
  this.xOffs = posX - parseInt(st.left,10);
  this.yOffs = posY - parseInt(st.top,10);
  Calendar.addEvent(document, "mousemove", Calendar.calDragIt);
  Calendar.addEvent(document, "mouseup", Calendar.calDragEnd);
};

Calendar._DN =   ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
Calendar._SDN =  ["Sun","Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
Calendar._FD =   0;
Calendar._MN =   ["January","February","March","April","May","June","July","August","September","October","November","December"];
Calendar._SMN =  ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
Calendar._TT =   {};
Calendar._TT.INFO = "About the calendar";
Calendar._TT.ABOUT =
  "DHTML Date/Time Selector\n" +
  "(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
  "For latest version visit: http://www.dynarch.com/projects/calendar/\n" +
  "Distributed under GNU LGPL.  See http://gnu.org/licenses/lgpl.html for details." +
  "\n\n" +
  "Date selection:\n" +
  "- Use the \xab, \xbb buttons to select year\n" +
  "- Use the " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " buttons to select month\n" +
  "- Hold mouse button on any of the above buttons for faster selection.";
  Calendar._TT.ABOUT_TIME = "\n\n" +
  "Time selection:\n" +
  "- Click on any of the time parts to increase it\n" +
  "- or Shift-click to decrease it\n" +
  "- or click and drag for faster selection.";
Calendar._TT.PREV_YEAR =       "Prev. year (hold for menu)";
Calendar._TT.PREV_MONTH =      "Prev. month (hold for menu)";
Calendar._TT.GO_TODAY =        "Go to Today";
Calendar._TT.NEXT_MONTH =      "Next month (hold for menu)";
Calendar._TT.NEXT_YEAR =       "Next year (hold for menu)";
Calendar._TT.SEL_DATE =        "Select date";
Calendar._TT.DRAG_TO_MOVE =    "Drag to move";
Calendar._TT.PART_TODAY =      " (today)";
Calendar._TT.DAY_FIRST =       "Display %s first";
Calendar._TT.WEEKEND =         "0,6";
Calendar._TT.CLOSE =           "Close";
Calendar._TT.TODAY =           "Today";
Calendar._TT.TIME_PART =       "(Shift-)Click or drag to change value";
Calendar._TT.DEF_DATE_FORMAT = "%Y-%m-%d";
Calendar._TT.TT_DATE_FORMAT =  "%a, %b %e";
Calendar._TT.WK =              "wk";
Calendar._TT.TIME =            "Time:";



// BEGIN: DATE OBJECT PATCHES

/** Adds the number of days array to the Date object. */
Date._MD = [31,28,31,30,31,30,31,31,30,31,30,31];

/** Constants used for time computations */
Date.SECOND = 1000 /* milliseconds */;
Date.MINUTE = 60 * Date.SECOND;
Date.HOUR   = 60 * Date.MINUTE;
Date.DAY    = 24 * Date.HOUR;
Date.WEEK   =  7 * Date.DAY;

Date.parseDate = function(str, fmt) {
  var today = new Date();
  var y = 0;
  var m = -1;
  var d = 0;
  var a = str.split(/\W+/);
  var b = fmt.match(/%./g);
  var i = 0, j = 0;
  var hr = 0;
  var min = 0;
  for (i = 0; i < a.length; ++i) {
    if (!a[i]) {
      continue;
    }
    switch (b[i]) {
      case "%d":
      case "%e":
        d = parseInt(a[i], 10);
      break;
      case "%m":
        m = parseInt(a[i], 10) - 1;
      break;
      case "%Y":
      case "%y":
        y = parseInt(a[i], 10);
        if (y < 100) {
          (y += (y > 29) ? 1900 : 2000);
        }
      break;
      case "%b":
      case "%B":
        for (j = 0; j < 12; ++j) {
          if (Calendar._MN[j].substr(0, a[i].length).toLowerCase() === a[i].toLowerCase()) { m = j; break; }
        }
      break;
      case "%H":
      case "%I":
      case "%k":
      case "%l":
        hr = parseInt(a[i], 10);
      break;
      case "%P":
      case "%p":
        if (/pm/i.test(a[i]) && hr < 12) {
          hr += 12;
        }
        else if (/am/i.test(a[i]) && hr >= 12) {
          hr -= 12;
        }
      break;
      case "%M":
        min = parseInt(a[i], 10);
      break;
    }
  }
  if (isNaN(y)) {
    y = today.getFullYear();
  }
  if (isNaN(m)) {
    m = today.getMonth();
  }
  if (isNaN(d)) {
    d = today.getDate();
  }
  if (isNaN(hr)) {
    hr = today.getHours();
  }
  if (isNaN(min)) {
    min = today.getMinutes();
  }
  if (y !== 0 && m !== -1 && d !== 0) {
    return new Date(y, m, d, hr, min, 0);
  }
  y = 0;
  m = -1;
  d = 0;
  for (i = 0; i < a.length; ++i) {
    if (a[i].search(/[a-zA-Z]+/) !== -1) {
      var t = -1;
      for (j = 0; j < 12; ++j) {
        if (Calendar._MN[j].substr(0, a[i].length).toLowerCase() === a[i].toLowerCase()) {
          t = j;
          break;
        }
      }
      if (t !== -1) {
        if (m !== -1) {
          d = m+1;
        }
        m = t;
      }
    }
    else if (parseInt(a[i], 10) <= 12 && m === -1) {
      m = a[i]-1;
    }
    else if (parseInt(a[i], 10) > 31 && y === 0) {
      y = parseInt(a[i], 10);
      if (y < 100) {
        (y += (y > 29) ? 1900 : 2000);
      }
    }
    else if (d === 0) {
      d = a[i];
    }
  }
  if (y === 0) {
    y = today.getFullYear();
  }
  if (m !== -1 && d !== 0) {
    return new Date(y, m, d, hr, min, 0);
  }
  return today;
};

/** Returns the number of days in the current month */
Date.prototype.getMonthDays = function(month) {
  var year = this.getFullYear();
  if (typeof month === "undefined") {
    month = this.getMonth();
  }
  if (((0 === (year%4)) && ( (0 !== (year%100)) || (0 === (year%400)))) && month === 1) {
    return 29;
  }
  else {
    return Date._MD[month];
  }
};

/** Returns the number of day in the year. */
Date.prototype.getDayOfYear = function() {
  var now = new Date(this.getFullYear(), this.getMonth(), this.getDate(), 0, 0, 0);
  var then = new Date(this.getFullYear(), 0, 0, 0, 0, 0);
  var time = now - then;
  return Math.floor(time / Date.DAY);
};

/** Returns the number of the week in year, as defined in ISO 8601. */
Date.prototype.getWeekNumber = function() {
  var d = new Date(this.getFullYear(), this.getMonth(), this.getDate(), 0, 0, 0);
  var DoW = d.getDay();
  d.setDate(d.getDate() - (DoW + 6) % 7 + 3); // Nearest Thu
  var ms = d.valueOf(); // GMT
  d.setMonth(0);
  d.setDate(4); // Thu in Week 1
  return Math.round((ms - d.valueOf()) / (7 * 864e5)) + 1;
};

/** Checks date and time equality */
Date.prototype.equalsTo = function(date) {
  return ((this.getFullYear() === date.getFullYear()) &&
    (this.getMonth() === date.getMonth()) &&
    (this.getDate() === date.getDate()) &&
    (this.getHours() === date.getHours()) &&
    (this.getMinutes() === date.getMinutes()));
};

Date.prototype.toISO = function(date) {
  var yyyy, mm, dd;
  yyyy = this.getFullYear();
  mm = this.getMonth()+1;
  dd = this.getDate();
  return yyyy+'-'+(mm.toString().length===2 ? mm : '0'+mm) + '-' + (dd.toString().length===2 ? dd : '0'+dd);
};

/** Set only the year, month, date parts (keep existing time) */
Date.prototype.setDateOnly = function(date) {
  var tmp = new Date(date);
  this.setDate(1);
  this.setFullYear(tmp.getFullYear());
  this.setMonth(tmp.getMonth());
  this.setDate(tmp.getDate());
};

/** Prints the date in a string according to the given format. */
Date.prototype.print = function (str) {
  var a, i, m, d, y, wn, w, s, hr, pm, ir, dy, min, sec, tmp, re;
  m = this.getMonth();
  d = this.getDate();
  y = this.getFullYear();
  wn = this.getWeekNumber();
  w = this.getDay();
  s = {};
  hr = this.getHours();
  pm = (hr >= 12);
  ir = (pm) ? (hr - 12) : hr;
  dy = this.getDayOfYear();
  if (ir === 0) {
    ir = 12;
  }
  min = this.getMinutes();
  sec = this.getSeconds();
  s["%a"] = Calendar._SDN[w]; // abbreviated weekday name [FIXME: I18N]
  s["%A"] = Calendar._DN[w]; // full weekday name
  s["%b"] = Calendar._SMN[m]; // abbreviated month name [FIXME: I18N]
  s["%B"] = Calendar._MN[m]; // full month name
  // FIXME: %c : preferred date and time representation for the current locale
  s["%C"] = 1 + Math.floor(y / 100); // the century number
  s["%d"] = (d < 10) ? ("0" + d) : d; // the day of the month (range 01 to 31)
  s["%e"] = d; // the day of the month (range 1 to 31)
  // FIXME: %D : american date style: %m/%d/%y
  // FIXME: %E, %F, %G, %g, %h (man strftime)
  s["%H"] = (hr < 10) ? ("0" + hr) : hr; // hour, range 00 to 23 (24h format)
  s["%I"] = (ir < 10) ? ("0" + ir) : ir; // hour, range 01 to 12 (12h format)
  s["%j"] = (dy < 100) ? ((dy < 10) ? ("00" + dy) : ("0" + dy)) : dy; // day of the year (range 001 to 366)
  s["%k"] = hr;    // hour, range 0 to 23 (24h format)
  s["%l"] = ir;    // hour, range 1 to 12 (12h format)
  s["%m"] = (m < 9) ? ("0" + (1+m)) : (1+m); // month, range 01 to 12
  s["%M"] = (min < 10) ? ("0" + min) : min; // minute, range 00 to 59
  s["%n"] = "\n";    // a newline character
  s["%p"] = pm ? "PM" : "AM";
  s["%P"] = pm ? "pm" : "am";
  // FIXME: %r : the time in am/pm notation %I:%M:%S %p
  // FIXME: %R : the time in 24-hour notation %H:%M
  s["%s"] = Math.floor(this.getTime() / 1000);
  s["%S"] = (sec < 10) ? ("0" + sec) : sec; // seconds, range 00 to 59
  s["%t"] = "\t";    // a tab character
  // FIXME: %T : the time in 24-hour notation (%H:%M:%S)
  s["%U"] = s["%W"] = s["%V"] = (wn < 10) ? ("0" + wn) : wn;
  s["%u"] = w + 1;  // the day of the week (range 1 to 7, 1 = MON)
  s["%w"] = w;    // the day of the week (range 0 to 6, 0 = SUN)
  // FIXME: %x : preferred date representation for the current locale without the time
  // FIXME: %X : preferred time representation for the current locale without the date
  s["%y"] = ('' + y).substr(2, 2); // year without the century (range 00 to 99)
  s["%Y"] = y;    // year with the century
  s["%%"] = "%";    // a literal '%' character

  re = /%./g;
  if (!Calendar.is_ie5 && !Calendar.is_khtml) {
    return str.replace(re, function (par) { return s[par] || par; });
  }
  a = str.match(re);
  for (i = 0; i < a.length; i++) {
    tmp = s[a[i]];
    if (tmp) {
      re = new RegExp(a[i], 'g');
      str = str.replace(re, tmp);
    }
  }

  return str;
};

Date.prototype.__msh_oldSetFullYear = Date.prototype.setFullYear;
Date.prototype.setFullYear = function(y) {
  var d = new Date(this);
  d.__msh_oldSetFullYear(y);
  if (d.getMonth() !== this.getMonth()) {
    this.setDate(28);
  }
  this.__msh_oldSetFullYear(y);
};

// END: DATE OBJECT PATCHES

function popup_calendar(id) {
  function popup_calendar_close(cal) {
    cal.destroy();                        // hide the calendar
  }
  function popup_calendar_selected(cal, date) {
    cal.sel.value = date;
    if (cal.dateClicked) {
      popup_calendar_close(cal);
    }
  }
  var el = document.getElementById(id);
  var cal = new Calendar(1, null, popup_calendar_selected, popup_calendar_close);
  cal.weekNumbers = false;
  cal.showsOtherMonths = true;
  cal.setRange(1900, 2070);        // min/max year allowed.
  cal.create();
  cal.setDateFormat('%Y-%m-%d');    // set the specified date format
  cal.parseDate(el.value);      // try to parse the text in field
  cal.sel = el;                 // inform it what input field we use
  cal.showAtElement(el.nextSibling, "Br");        // show the calendar
  return false;
}

// Based on Tigra Calendar v4.0.4 (10/23/2009)
// http://www.softcomplex.com/products/tigra_calendar/
// Public Domain Software... You're welcome.

function tcal (a_cfg) {
  var img;
  window.A_TCALS = window.A_TCALS || [];
  window.A_TCALSIDX = window.A_TCALSIDX || [];
  a_tpl = {
	'months' : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
	'weekdays' : ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
	'weekstart': 0,
	'centyear'  : 70, // 2 digit years less than 'centyear' are in 20xx, othewise in 19xx.
	'imgpath' : (typeof base_url!=='undefined' ? base_url : '/')+'img/sysimg/?img=tcal/' // directory with calendar images
   };
  this.a_cfg = a_cfg;
  this.a_tpl = a_tpl;
  this.s_id = this.a_cfg.id ? this.a_cfg.id : A_TCALS.length;
  this.e_input = geid(this.a_cfg.controlname);
  window.A_TCALS[this.s_id] = this;
  window.A_TCALSIDX[window.A_TCALSIDX.length] = this;
  this.s_iconId = 'tcalico_' + this.s_id;
  this.e_icon = geid(this.s_iconId);
  if (!this.e_icon) {
    img =     document.createElement('img');
    img.setAttribute('id',this.s_iconId);
    img.setAttribute('src',a_tpl.imgpath + 'cal.gif&v=2');
    img.setAttribute('class','tcalIcon');
    img.setAttribute('alt','Open Calendar');
    img.setAttribute('onclick','A_TCALS[\'' + this.s_id + '\'].f_toggle()');
    this.e_input.parentNode.insertBefore(img, this.e_input.nextSibling );
    this.e_icon = geid(this.s_iconId);
  }
}

tcal.prototype.f_generDate = function(d_date) {
  return (
    d_date.getFullYear() + "-" +
    (d_date.getMonth() < 9 ? '0' : '') + (d_date.getMonth() + 1) + "-" +
    (d_date.getDate() < 10 ? '0' : '') + d_date.getDate()
  );
}

tcal.prototype.f_parseDate = function(s_date) {
  var d_numdays, n_day, re_date;
  re_date = /^\s*(\d{2,4})\-(\d{1,2})\-(\d{1,2})\s*$/;
  if (!re_date.exec(s_date)){
    return alert ("Invalid date: '" + s_date + "'.\nAccepted format is yyyy-mm-dd.")
  }
  n_day = Number(RegExp.$3),
  n_month = Number(RegExp.$2),
  n_year = Number(RegExp.$1);
  if (n_year < 100){
    n_year += (n_year < this.a_tpl.centyear ? 2000 : 1900);
  }
  if (n_month < 1 || n_month > 12){
    return alert ("Invalid month value: '" + n_month + "'.\nAllowed range is 01-12.");
  }
  d_numdays = new Date(n_year, n_month, 0);
  if (n_day > d_numdays.getDate()){
    return alert("Invalid day of month value: '" + n_day + "'.\nAllowed range for selected month is 01 - " + d_numdays.getDate() + ".");
  }
  return new Date (n_year, n_month - 1, n_day);
}

tcal.prototype.f_hide = function(n_date) {
  if (n_date=='0000-00-00'){
    this.e_input.value = '';
    this.e_input.focus();
    this.e_input.blur();
  }
  else if (n_date){
    this.e_input.value = this.f_generDate(new Date(n_date));
    this.e_input.focus();
    this.e_input.blur();
  }
  if (!this.b_visible){
    return;
  }
  if (this.e_iframe){
    this.e_iframe.style.visibility = 'hidden';
  }
  this.e_div.style.visibility = 'hidden';
  this.e_icon = geid(this.s_iconId);
  this.e_icon.src = this.a_tpl.imgpath + 'cal.gif&v=2';
  this.e_icon.title = 'Open Calendar';
  this.b_visible = false;
}

tcal.prototype.f_relDate = function(d_date, d_diff, s_units) {
  var d_result, s_units;
  s_units = (s_units == 'y' ? 'FullYear' : 'Month');
  d_result = new Date(d_date);
  d_result['set' + s_units](d_date['get' + s_units]() + d_diff);
  if (d_result.getDate() != d_date.getDate()){
    d_result.setDate(0);
  }
  return ' onclick="A_TCALS[\'' + this.s_id + '\'].f_update(' + d_result.valueOf() + ')"';
}

tcal.prototype.f_reset_time = function(d_date) {
  d_date.setHours(12);
  d_date.setMinutes(0);
  d_date.setSeconds(0);
  d_date.setMilliseconds(0);
  return d_date;
}

tcal.prototype.f_show = function(d_date) {
  this.e_div = geid('tcal');
  if (!this.e_div) {
    this.e_div = document.createElement("DIV");
    this.e_div.id = 'tcal';
    document.body.appendChild(this.e_div);
  }
  this.e_iframe =  geid('tcalIF');
  if (isIE_lt7 && !this.e_iframe) {
    this.e_iframe = document.createElement("IFRAME");
    this.e_iframe.style.filter = 'alpha(opacity=0)';
    this.e_iframe.id = 'tcalIF';
    this.e_iframe.src = this.a_tpl.imgpath + 'pixel.gif';
    document.body.appendChild(this.e_iframe);
  }
  tcal_hideAll();
  this.e_icon = geid(this.s_iconId);
  if (!this.f_update()){
    return;
  }
  this.e_div.style.visibility = 'visible';
  if (this.e_iframe){
    this.e_iframe.style.visibility = 'visible';
  }
  // change icon and status
  this.e_icon.src = this.a_tpl.imgpath + 'no_cal.gif&v=2';
  this.e_icon.title = 'Close Calendar';
  this.b_visible = true;
}

tcal.prototype.f_toggle = function() {
  return this.b_visible ? this.f_hide() : this.f_show();
}

tcal.prototype.f_update = function(d_date) {
  var
    a_class, d_current, d_today, d_selected, d_firstday, i,
    n_width, n_height, n_top, n_left, n_date, n_month, n_wday, s_html;
  d_today = this.a_cfg.today ? this.f_parseDate(this.a_cfg.today) : this.f_reset_time(new Date());
  d_selected = this.e_input.value == '' ? (this.a_cfg.selected ? this.f_parseDate(this.a_cfg.selected) : d_today) : this.f_parseDate(this.e_input.value);
  if (!d_date){
    d_date = d_selected;
  }
  else if (typeof(d_date) == 'number'){
    d_date = this.f_reset_time(new Date(d_date));
  }
  else if (typeof(d_date) == 'string'){
    d_date = this.f_parseDate(d_date);
  }
  if (!d_date){
    return false;
  }
  d_firstday = new Date(d_date);
  d_firstday.setDate(1);
  d_firstday.setDate(1 - (7 + d_firstday.getDay() - this.a_tpl.weekstart) % 7);
  s_html =
    '<table class="ctrl"><tbody><tr>'+
    '<td' + this.f_relDate(d_date, -10, 'y') + ' title="Previous Decade">' +
    '<img src="' + this.a_tpl.imgpath + 'prev_dec.gif" /></td>' +
    '<td' + this.f_relDate(d_date, -1, 'y') + ' title="Previous Year">' +
    '<img src="' + this.a_tpl.imgpath + 'prev_year.gif" /></td>' +
    '<td' + this.f_relDate(d_date, -1) + ' title="Previous Month">' +
    '<img src="' + this.a_tpl.imgpath + 'prev_mon.gif" /></td>' +
    '<th>' + this.a_tpl.months[d_date.getMonth()] + ' ' + d_date.getFullYear()+ '</th>' +
    '<td' + this.f_relDate(d_date, 1) + ' title="Next Month">' +
    '<img src="' + this.a_tpl.imgpath + 'next_mon.gif" /></td>' +
    '<td' + this.f_relDate(d_date, 1, 'y') + ' title="Next Year">' +
    '<img src="' + this.a_tpl.imgpath + 'next_year.gif" /></td></td>'+
    '<td' + this.f_relDate(d_date, 10, 'y') + ' title="Next Decade">' +
    '<img src="' + this.a_tpl.imgpath + 'next_dec.gif" /></td></td>'+
    '</tr></tbody></table>' +
    '<table><tbody><tr class="wd">';
  for (i=0; i<7; i++){
    s_html += '<th>' + this.a_tpl.weekdays[(this.a_tpl.weekstart + i) % 7] + '</th>';
  }
  s_html += '</tr>' ;
  d_current = new Date(d_firstday);
  while (d_current.getMonth() == d_date.getMonth() ||
  d_current.getMonth() == d_firstday.getMonth()) {
    s_html +='<tr>';
    for (n_wday = 0; n_wday < 7; n_wday++) {
      a_class = [];
      n_date = d_current.getDate();
      n_month = d_current.getMonth();
      // other month
      if (d_current.getMonth() != d_date.getMonth()){
        a_class[a_class.length] = 'othermonth';
      }
      // weekend
      if (d_current.getDay() == 0 || d_current.getDay() == 6){
        a_class[a_class.length] = 'weekend';
      }
      // today
      if (d_current.valueOf() == d_today.valueOf()){
        a_class[a_class.length] = 'today';
      }
      // selected
      if (d_current.valueOf() == d_selected.valueOf()){
        a_class[a_class.length] = 'selected';
      }
      s_html += '<td onclick="A_TCALS[\'' + this.s_id + '\'].f_hide(' + d_current.valueOf() + ')"' + (a_class.length ? ' class="' + a_class.join(' ') + '">' : '>') + n_date + '</td>';
      d_current.setDate(++n_date);
    }
    s_html +='</tr>';
  }
  s_html +=
    '</tbody></table>'+
    '<table class="today">' +
    '<tbody><tr>' +
    '<th style="width:50%" onclick="A_TCALS[\'' + this.s_id + '\'].f_hide(' + d_today.valueOf() + ')">Today</th>'+
    '<th style="width:50%" onclick="A_TCALS[\'' + this.s_id + '\'].f_hide(\'0000-00-00\')">Clear</th>'+
    '</tr></tbody></table>';
  // update HTML, positions and sizes
  this.e_div.innerHTML = s_html;
  var n_width  = this.e_div.offsetWidth;
  var n_height = this.e_div.offsetHeight;
  var n_top  = f_getPosition (this.e_icon, 'Top') + this.e_icon.offsetHeight;
  var n_left = f_getPosition (this.e_icon, 'Left') - n_width + this.e_icon.offsetWidth;
  if (n_left < 0){
    n_left = 0;
  }
  this.e_div.style.left = n_left + 'px';
  this.e_div.style.top  = n_top + 'px';
  if (this.e_iframe) {
    this.e_iframe.style.left = n_left + 'px';
    this.e_iframe.style.top  = n_top + 'px';
    this.e_iframe.style.width = (n_width + 6) + 'px';
    this.e_iframe.style.height = (n_height + 6) +'px';
  }
  return true;
}

function f_getPosition (e_elemRef, s_coord) {
  var n_pos, n_offset, e_elem;
  n_pos = 0;
  e_elem = e_elemRef;
  while (e_elem) {
    n_offset = e_elem["offset" + s_coord];
    n_pos += n_offset;
    e_elem = e_elem.offsetParent;
  }
  // margin correction in some browsers
  if (isIEMac){
    n_pos += parseInt(document.body[s_coord.toLowerCase() + 'Margin']);
  }
  else if (isSafari){
    n_pos -= n_offset;
  }
  e_elem = e_elemRef;
  while (e_elem != document.body) {
    n_offset = e_elem["scroll" + s_coord];
    if (n_offset && e_elem.style.overflow == 'scroll'){
      n_pos -= n_offset;
    }
    e_elem = e_elem.parentNode;
  }
  return n_pos;
}

function tcal_hideAll() {
  var i;
  if (!window.A_TCALSIDX){
    return;
  }
  for (i=0; i<window.A_TCALSIDX.length; i++){
    window.A_TCALSIDX[i].f_hide();
  }
}