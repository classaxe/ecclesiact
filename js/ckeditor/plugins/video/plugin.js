// 1.0.3
/* First line must show version number - update as builds change

Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license

Version History:
  1.0.3 (2014-04-18)
    1) Completely rewritten for CKEditor 4.3.4
  1.0.2 (2011-05-11)
    1) Changes to force IE9 to use Firefox safe code for the FCK_ECLTags.Redraw()
       function - the window.performance variable is the test for IE9
  1.0.1 (2010-12-01)
    1) Unknown changes
  1.0.0 (2010-11-19)
    1) Initial release
*/

'use strict';

( function() {
  CKEDITOR.plugins.add(
    'video', {
      requires: 'dialog,widget',
      lang:     'en',
      icons:    'video',
      hidpi:    true,
      init: function( editor ) {
        var lang = editor.lang.video;
        CKEDITOR.dialog.add(
          'video',
          this.path + 'dialogs/video.js'
        );
        editor.widgets.add(
          'video', {
            dialog:     'video',
            draggable:  false,
            pathName:   lang.pathName,
            template:   '<span class="cke_video">[video: ]</span>',
            downcast:   function() {
              return new CKEDITOR.htmlParser.text(this.data.name);
            },
            init:   function() {
              var i, tag, tag_arr, tag_bits;
              tag = this.element.getText().slice(7,-1).trim();
              tag_arr = tag.split('|');
              this.setData('tag_flv',tag_arr.shift());
              if (tag_arr.length>0){
                this.setData('tag_jpg',tag_arr.shift());
              }
              if (tag_arr.length>0){
                this.setData('tag_width',tag_arr.shift());
              }
              if (tag_arr.length>0){
                this.setData('tag_height',tag_arr.shift());
              }
            },
            data:   function( data ) {
              var tag =
                '[video: ' +
                (this.data.tag_flv ?             this.data.tag_flv : '') +
                (this.data.tag_jpg ?        '|'+ this.data.tag_jpg : '') +
                (this.data.tag_width ?      '|'+ this.data.tag_width : '') +
                (this.data.tag_height ?     '|'+ this.data.tag_height : '') +
                ']';
              this.element.setText(tag);
              this.setData('name', tag );
            }
          }
        );
        if ( editor.addMenuItems ){
          editor.addMenuGroup( 'video', 20 );
          editor.addMenuItems( {
            video: {
              label:    lang.edit,
              command:  'video',
              group:    'video',
              order:    1,
              icon:     'video'
            }
          } );
          if ( editor.contextMenu ){
            editor.contextMenu.addListener(
              function( element, selection ){
                if (
                  !element ||
                  !element.getChild ||
                  !element.getChild(0).getAttribute ||
                  element.getChild(0).getAttribute('data-widget')!=='video'
                ){
                  return null;
                }
                return { video : CKEDITOR.TRISTATE_OFF };
              }
            );
          }
        }
        editor.ui.addButton && editor.ui.addButton(
          'Video', {
            label:      lang.toolbar,
            command:    'video',
            toolbar:    'insert,5',
            icon:       'video'
          }
        );
      },

      afterInit: function( editor ) {
        editor.dataProcessor.dataFilter.addRules( {
          text: function( text, node ) {
            var dtd = node.parent && CKEDITOR.dtd[ node.parent.name ];
            // Skip the case when video is in elements like <title> or <textarea>
            // but upcast video in custom elements (no DTD).
            if ( dtd && !dtd.span ){
              return;
            }
            var regExp = /\[video\:[^\]]+\]/g;
            return text.replace(
              regExp,
              function( match ) {
                var innerElement = new CKEDITOR.htmlParser.element(
                  'span', {
                    'class': 'cke_video'
                  }
                );
                innerElement.add(
                  new CKEDITOR.htmlParser.text( match )
                );
                var widgetWrapper = editor.widgets.wrapElement(
                  innerElement,
                  'video'
                );
                return widgetWrapper.getOuterHtml();
              }
            );
          }
        } );
      }
  } );
} )();
