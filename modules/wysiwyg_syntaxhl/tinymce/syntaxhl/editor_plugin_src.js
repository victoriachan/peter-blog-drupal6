// $Id: editor_plugin_src.js,v 1.1.2.7 2009/12/06 21:49:00 meecect Exp $
/**
 * Copied from tinymce example plugin and modifed to suite its needs.
 *
 * http://27smiles.com
 * @author Richard Grundy
 *
 * modified 2009 Clifford Meece
 * http://cliffordmeece.com
 * added onBeforeSetContent and onPostProcess handlers
 * fixed onNodeChange (needs PRE, not IMG)
 */
 
(function() {
	//Load the language file.
	tinymce.PluginManager.requireLangPack('syntaxhl');
	
	tinymce.create('tinymce.plugins.SyntaxHL', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			var t = this;
						
			t.editor = ed;
			t.url = url;
			
			function isSyntaxElm(n) {
				return /^(wysiwyg-syntaxhl)/.test(n.className);
			};
			
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
			ed.addCommand('mceSyntaxHL', function() {
				ed.windowManager.open({
					file : url + '/dialog.php?path='+escape(tinyMCE.baseURL)+'&random=' + Math.random(),
					width : 450 + parseInt(ed.getLang('syntaxhl.delta_width', 0)),
					height : 400 + parseInt(ed.getLang('syntaxhl.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});
			
			// Register button
			ed.addButton('syntaxhl', {
				title : 'syntaxhl.desc',
				cmd : 'mceSyntaxHL',
				image : url + '/img/highlight.gif'
			});
			
			// Add a node change handler, selects the button in the UI when a <pre> element is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('syntaxhl', n.nodeName == 'PRE');
			});
			
			ed.onInit.add(function() {
				if (ed && ed.plugins.contextmenu) {
					ed.plugins.contextmenu.onContextMenu.add(function(th, m, e) {
						if (e.nodeName == 'PRE') {
							m.add({title : 'Edit syntax settings', icon : '', cmd : 'mceSyntax'});
						}
					});
				}
			});
			// convert any {}-macro tags to pre's 
			ed.onBeforeSetContent.add(t._macroToPre, t);
			
			// When getting content out of the editor
			// convert pre tags to {}-macro tags
			// It will only do it's job on none ie browsers
			// because with ie, tiny is collapsing whitespace somewhere
			ed.onBeforeGetContent.add(function(ed, o) {
        t._preToMacro(ed,o);
      });
      
    //  ed.onPostProcess.add(function(ed, o) {
    //    if (o.save){
    //      if (tinymce.isIE) {
    //        window.alert('doing postprocess');
    //        window.alert(o.content);
    //        o.content = o.content.replace(/<pre class="wysiwyg-syntaxhl ([^"]*)">/g,'{syntaxhighlighter $1}' );
    //        o.content = o.content.replace(/<\/pre>/g,'{/syntaxhighlighter}' );
    //        window.alert(o.content);
    //        // whitespace preserved here so far
    //      }
    //    }
    //  });
      
    //  ed.onSaveContent.add(function(ed, o) {
    //      // but now, in ie, whitespace is collapsed
    //      window.alert(o.content);
    //  });
			
		},

		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} n Name of the control to create.
		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		createControl : function(n, cm) {
			return null;
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'Syntax Highlighter',
				author : 'Richard Grundy',
				authorurl : 'http://27smiles.com',
				infourl : 'http://27smiles.com',
				version : "1.0"
			};
		},
		
		// Private methods
		_macroToPre : function(ed, o) {
		  var nlChar;
		  if (tinymce.isIE)
        nlChar = '\r\n';
      else
        nlChar = '\n';
		  o.content = o.content.replace(/(<p>)?(<P>)?{syntaxhighlighter ([^}]*)}/g, '<pre class="wysiwyg-syntaxhl $3">' );
		  o.content = o.content.replace(/{\/syntaxhighlighter}(<\/p>)?(<\/P>)?/g,'</pre>');

		},
		
		_preToMacro : function(ed, o) {
		  if (o.save) {   
		    // only do this on page save, otherwise it gets invoked when editor loads as well
		    // can probably determine when, say, html source button is clicked and do something
		    // similar.  HTML source should probably show the macros and not just the <pre>'s   

        if (!tinymce.isIE) {
          // do this routine on non-ie browsers
          // using dom instead of regexp because oterwise it is very hard to 
          // not match </pre> tags that might be in the code syntax block.
          // on ie, whitespace is collapsed, somewhere between onPostProcess and onSaveContent
          // so I give up and leave the elements as pre tags until I can find
          // a solution  		  
    		  var syntaxElements = ed.dom.select('pre.wysiwyg-syntaxhl');
          for (var i = 0; i < syntaxElements.length; i++) {
            var style = ed.dom.getAttrib(syntaxElements[i], 'class');
            style = style.replace(/(wysiwyg-syntaxhl\s*)/g,'');
            // always use the getText function, because innerHTML causes ie to remove whitespace
            var macroString = '{syntaxhighlighter ' + style +'}' + ed.dom.encode(this._getText(syntaxElements[i])) + '{/syntaxhighlighter}';
            ed.dom.setOuterHTML(syntaxElements[i],macroString);
    		  }
    		}
  		}
		},
		
		_getText : function(obj) {
      // return the data of obj if its a text node
      if (obj.nodeType == 3) return obj.nodeValue;
      var txt = new Array(),i=0;
      // loop over the children of obj and recursively pass them back to this function
      while(obj.childNodes[i]) {
    		txt[txt.length] = this._getText(obj.childNodes[i]);
    		i++;
    	}
      // return the array as a string
      return txt.join("");
    }
	});

	// Register plugin
	tinymce.PluginManager.add('syntaxhl', tinymce.plugins.SyntaxHL);
})();