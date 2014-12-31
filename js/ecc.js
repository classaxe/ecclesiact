// 1.0.8
/*
Version History:
  1.0.8 (2013-09-23)
    1) Now runs external links onload - was missing before - and also scans
       class of type 'ecc_wide'
  1.0.7 (2013-01-14)
    1) Added ecc.cal_goto()
  1.0.6 (2012-10-13)
    1) Added ecc.importLib()
    2) Now automatically includes bible ref tagging with definable bible version
  1.0.5 (2012-10-13)
    1) ecc.load() now accepts a limit parameter
  1.0.4 (2012-10-11)
    1) ecc.load() now sets up handlers for paging controls
    2) ecc.externalLinks() now uses JQuery selectors to limit scope
  1.0.3 (2012-10-10)
    1) Added JSON method to ecc
    2) Added list_podcasts__paging()
  1.0.2 (2011-09-27)
    1) Added ecc.base_url property to hold server URL, allowing shorter embed code
  1.0.1 (2011-08-30)
    1) ecc.load() now requires full URL
  1.0.0 (2011-08-30)
    1) Initial release
*/
var ap_instances = [];

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
  // This only works when the player comes from the same domain as the web page
  var i;
  for (i=0; i<ap_instances.length; i++) {
    try {
      if(ap_instances[i] != playerID){
        $('#audioplayer'+ap_instances[i].toString())[0].SetVariable("closePlayer",1);
      }
      else{
        $('#audioplayer'+ap_instances[i].toString())[0].SetVariable("closePlayer", 0);
      }
    }
    catch(e){ }
  }
}

function popWin(url,winName,features,width,height,centre) {
  var availx, availy, posx, posy, theWin;
  if (centre === "centre") {
    availx = screen.availWidth;
    availy = screen.availHeight;
    posx = (availx - width)/2;
    posy = (availy - height)/2;
    theWin = window.open(
      url,
      winName,
      features+',width='+width+',height='+height+',left='+posx+',top='+posy
    );
  }
  else {
    theWin = window.open(
      url,
      winName,
      features+',width='+width+',height='+height+',left=25,top=25'
    );
  }
  if (!theWin){
    alert(
      'ERROR:\n\n'+
      'This site tried to open a popup window\n'+
      'but was prevented from doing so.\n\n'+
      'Please disable any popup blockers you may\n'+
      'have enabled for this site.'
    );
    return false;
  }
  theWin.focus();
  return theWin;
}

ecc = {
  base_url: "%SYS_URL%",
  bible_version: '',
  cal_goto: function(ctl,offset) {
    var d, MM, YYYY, YYYYMM;
    YYYYMM = $('#'+ctl)[0].value;
    YYYY =   YYYYMM.substr(0,4);
    MM =	 YYYYMM.substr(5,2);
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
    $('.cal_control').each(function(){
      this.className = 'cal_control_disabled';
      this.onclick=null;
      this.onmouseover=null;
      this.onmousedown=null;
      this.onmouseout=null;
    });
    window['list_calendar__paging'](0,YYYY,MM);
  },
  cal_list: function(YYYYMMDD,isAdmin){
    alert('Not yet implemented');
    return false;
  },
  cal_setup: function(){
    $('.cal_control').mouseover(function(){ this.className='cal_control_over';});
    $('.cal_control').mousedown(function(){ this.className='cal_control_down';});
    $('.cal_control').mouseout(function(){ this.className='cal_control';});
  },
  externalLinks: function() {
    $('.ecc a,.ecc_wide a').each(function(){
      if (typeof this.href==='string' && this.href){
        if(this.title==='View Details'){
          this.target='_blank';
        }
        if(this.rel==='external'){
          this.target='_blank';
        }
        if ($(this).hasClass('rss-item')){
          this.target='_blank';
       }
        if (this.rel==='disabled'){
          this.disabled=true;
        }
      }
    });
  },
  importLib: function(libPath,callback) {
    var newLib = document.createElement("script");
    newLib.onload = callback;
    newLib.src = libPath;
    document.head.appendChild(newLib);
  },
  load: function(path,mode,div,limit,offset,YYYY,MM){
    window['list_'+mode+'__paging'] = function(_offset,_YYYY,_MM) {
      var offset = (typeof _offset!=='undefined' ? _offset : '');
      var YYYY = (typeof _YYYY!=='undefined' ? _YYYY : '');
      var MM = (typeof _MM!=='undefined' ? _MM : '');
      ecc.load(path, mode, div, limit, offset, YYYY, MM);
      return false;
    }
    $.getJSON(
      ecc.base_url+path+'/js/'+mode+'?id='+div+
        (typeof limit!=='undefined' ? '&limit='+limit : '')+
        (typeof offset!=='undefined' ? '&offset='+offset : '')+
        (typeof YYYY!=='undefined' ? '&YYYY='+YYYY : '')+
        (typeof MM!=='undefined' ? '&MM='+MM : '')+
        '&callback=?',
      null,
      function(data) {
        $('#'+div)[0].innerHTML=data.html;
        eval(data.js);
        ecc.externalLinks();
        if (typeof Logos!=='undefined'){
          Logos.ReferenceTagging.tag($('#'+div)[0]);
        }
      }
    );
  }
}
ecc.importLib(
  'http:\/\/bible.logos.com\/jsapi\/referencetagging.js',
  function(){
    Logos.ReferenceTagging.lbsBibleVersion = ecc.bible_version;
    Logos.ReferenceTagging.lbsLinksOpenNewWindow = true;
    Logos.ReferenceTagging.lbsLogosLinkIcon = "light";
    Logos.ReferenceTagging.lbsNoSearchTagNames = [ "h1", "h2", "h3" ];
    Logos.ReferenceTagging.lbsTargetSite = "biblia";
  }
);