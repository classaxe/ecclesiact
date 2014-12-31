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
  1.0.1 (2010-11-19)
    1) Removed switch case for safari / opera - now handled by caller
    2) Sets dialog for firefox and IE differently
  1.0.0 (2009-03-16)
    1) Initial release for FCK Editor 2.5.b
*/

'use strict';

( function() {
  CKEDITOR.plugins.add(
    'audio', {
      requires: 'dialog,widget',
      lang:     'en',
      icons:    'audio',
      hidpi:    true,
      init: function( editor ) {
        var lang = editor.lang.audio;
        CKEDITOR.dialog.add(
          'audio',
          this.path + 'dialogs/audio.js'
        );
        editor.widgets.add(
          'audio', {
            dialog:     'audio',
            draggable:  false,
            pathName:   lang.pathName,
            template:   '<span class="cke_audio">[audio: ]</span>',
            downcast:   function() {
              return new CKEDITOR.htmlParser.text(this.data.name);
            },
            init:   function() {
              var i, tag, tag_arr, tag_bits;
              tag = this.element.getText().slice(7,-1).trim();
              tag_arr = tag.split('|');
              this.setData('tag_path',tag_arr.shift());
              for(i in tag_arr){
                tag_bits = tag_arr[i].split('=');
                switch(tag_bits[0]){
                  case 'autostart':
                    this.setData('tag_autostart',   (tag_bits.length==2 ? tag_bits[1] : ''));
                  break;
                  case 'loop':
                    this.setData('tag_loop',        (tag_bits.length==2 ? tag_bits[1] : ''));
                  break;
                  case 'width':
                    this.setData('tag_width',       (tag_bits.length==2 ? tag_bits[1] : ''));
                  break;
                  case 'height':
                    this.setData('tag_height',      (tag_bits.length==2 ? tag_bits[1] : ''));
                  break;
                }
              }
            },
            data:   function( data ) {
              var tag =
                '[audio: ' +
                (this.data.tag_path ?       this.data.tag_path : '') +
                (this.data.tag_autostart ?  '|autostart='+this.data.tag_autostart : '') +
                (this.data.tag_loop ?       '|loop='+this.data.tag_loop : '') +
                (this.data.tag_width ?      '|width='+this.data.tag_width : '') +
                (this.data.tag_height ?     '|height='+this.data.tag_height : '') +
                ']';
              this.element.setText(tag);
              this.setData('name', tag );
            }
          }
        );
        if ( editor.addMenuItems ){
          editor.addMenuGroup( 'audio', 20 );
          editor.addMenuItems( {
            audio: {
              label:    lang.edit,
              command:  'audio',
              group:    'audio',
              order:    1,
              icon:     'audio'
            }
          } );
          if ( editor.contextMenu ){
            editor.contextMenu.addListener(
              function( element, selection ){
                if (
                  !element ||
                  !element.getChild ||
                  !element.getChild(0).getAttribute ||
                  element.getChild(0).getAttribute('data-widget')!=='audio'
                ){
                  return null;
                }
                return { audio : CKEDITOR.TRISTATE_OFF };
              }
            );
          }
        }
        editor.ui.addButton && editor.ui.addButton(
          'Audio', {
            label:      lang.toolbar,
            command:    'audio',
            toolbar:    'insert,5',
            icon:       'audio'
          }
        );
      },

      afterInit: function( editor ) {
        editor.dataProcessor.dataFilter.addRules( {
          text: function( text, node ) {
            var dtd = node.parent && CKEDITOR.dtd[ node.parent.name ];
            // Skip the case when audio is in elements like <title> or <textarea>
            // but upcast audio in custom elements (no DTD).
            if ( dtd && !dtd.span ){
              return;
            }
            var regExp = /\[audio\:[^\]]+\]/g;
            return text.replace(
              regExp,
              function( match ) {
                var innerElement = new CKEDITOR.htmlParser.element(
                  'span', {
                    'class': 'cke_audio'
                  }
                );
                innerElement.add(
                  new CKEDITOR.htmlParser.text( match )
                );
                var widgetWrapper = editor.widgets.wrapElement(
                  innerElement,
                  'audio'
                );
                return widgetWrapper.getOuterHtml();
              }
            );
          }
        } );
      }
  } );
} )();
