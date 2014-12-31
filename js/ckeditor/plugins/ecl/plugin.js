// 1.0.9
/* First line must show version number - update as builds change

Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license

Version History:
  1.0.9 (2014-04-18)
    1) Completely rewritten for CKEditor 4.3.4
    2) Fixed monospaced tag selector dropdown options font for firefox
  1.0.8 (2011-05-11)
    1) Changes to force IE9 to use Firefox safe code for the FCK_ECLTags.Redraw()
       function - the window.performance variable is the test for IE9
  1.0.7 (2010-11-19)
    1) Removed switch case for safari / opera - now handled by caller
    2) Sets dialog for firefox and IE differently
  1.0.6 (2009-03-16)
    1) Unknown changes
  1.0.5 (2009-03-09)
    1) Unknown changes
  1.0.4 (2008-11-25)
    1) Unknown changes
  1.0.3 (2008-??-??)
    1) Unknown release
  1.0.2 (2008-04-22)
    1) Big changes to fck_ecl.html - all JS now direct from server
  1.0.1 (2007-11-21)
    1) Fix for firefox

*/

'use strict';

( function() {
  CKEDITOR.plugins.add(
    'ecl', {
      requires: 'ajax,dialog,widget,xml',
      lang:     'en',
      icons:    'ecl',
      hidpi:    true,
      init: function( editor ) {
        var lang = editor.lang.ecl;
        CKEDITOR.dialog.add(
          'ecl',
          this.path + 'dialogs/ecl.js'
        );
        editor.widgets.add(
          'ecl', {
            dialog:     'ecl',
            draggable:  false,
            pathName:   lang.pathName,
            template:   '<span class="cke_ecl">[ECL][/ECL]</span>',
            downcast:   function() {
              return new CKEDITOR.htmlParser.text(this.data.name);
            },
            init:   function() {
              var tag = this.element.getText().slice( 5, -6);
              var tag_arr = tag.split(':');
              this.setData('tag_t',tag_arr[0]);
              this.setData('tag_i',(tag_arr.length==2 ? tag_arr[1] : ''));
            },
            data:   function( data ) {
              var tag =
                '[ECL]' +
                this.data.tag_t +
                (this.data.tag_i ? ':'+this.data.tag_i : '') +
                '[/ECL]';
              this.element.setText(tag);
              this.setData('name', tag );
            }
          }
        );
        if ( editor.addMenuItems ){
          editor.addMenuGroup( 'ecl', 20 );
          editor.addMenuItems( {
            ecl: {
              label:    lang.edit,
              command:  'ecl',
              group:    'ecl',
              order:    1,
              icon:     'ecl'
            }
          } );
          if ( editor.contextMenu ){
            editor.contextMenu.addListener(
              function( element, selection ){
                if (
                  !element ||
                  !element.getChild ||
                  !element.getChild(0).getAttribute ||
                  element.getChild(0).getAttribute('data-widget')!=='ecl'
                ){
                  return null;
                }
                return { ecl : CKEDITOR.TRISTATE_OFF };
              }
            );
          }
        }
        editor.ui.addButton && editor.ui.addButton(
          'ECL', {
            label:      lang.toolbar,
            command:    'ecl',
            toolbar:    'insert,5',
            icon:       'ecl'
          }
        );
      },

      afterInit: function( editor ) {
        editor.dataProcessor.dataFilter.addRules( {
          text: function( text, node ) {
            var dtd = node.parent && CKEDITOR.dtd[ node.parent.name ];
            // Skip the case when ecl is in elements like <title> or <textarea>
            // but upcast ecl in custom elements (no DTD).
            if ( dtd && !dtd.span ){
              return;
            }
            var regExp = /\[ECL\][^\[]+\[\/ECL\]/g;
            return text.replace(
              regExp,
              function( match ) {
                var innerElement = new CKEDITOR.htmlParser.element(
                  'span', {
                    'class': 'cke_ecl'
                  }
                );
                innerElement.add(
                  new CKEDITOR.htmlParser.text( match )
                );
                var widgetWrapper = editor.widgets.wrapElement(
                  innerElement,
                  'ecl'
                );
                return widgetWrapper.getOuterHtml();
              }
            );
          }
        } );
      }
  } );
} )();
