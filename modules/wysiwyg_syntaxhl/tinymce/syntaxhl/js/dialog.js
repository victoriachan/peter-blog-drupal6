// $Id: dialog.js,v 1.1.2.6 2009/12/06 17:42:42 meecect Exp $
tinyMCEPopup.requireLangPack();
var SyntaxHLDialog = {


  init : function() {

    var f = document.forms[0], textarea_output, options = '';
    ed = tinyMCEPopup.editor;
    n = ed.selection.getNode();
    var languages;
    if (ed && ed.plugins.inlinepopups) {
      languages = parent.Drupal.settings.wysiwyg_syntaxhl.languages;
    } else {
      languages = window.opener.Drupal.settings.wysiwyg_syntaxhl.languages;
    }
      
    for (i=0; i < languages.length;++i){
      var optn = document.createElement("OPTION");
      optn.text = languages[i]['text'];
      optn.value = languages[i]['value'];
      document.syntaxhl.syntaxhl_language.options.add(optn);
    }
    
    if (n.nodeName == 'PRE') {
      // remove placeholder class name and split remaining portion by ';'
      var style = ed.dom.getAttrib(n, 'class');
      style = style.replace(/wysiwyg-syntaxhl\s*/g,'')
      var a = style.split(';');
      for (var i = 0; i < a.length; i++) {
        // Remove unnecessary whitespace
        a[i] = a[i].replace(/\s+/g, ''); 
        if (a[i] != '') {
          // split name and value pairs
          var b = a[i].split(':');
          switch (b[0]) {
      			case "brush":
      			  // loop through select box looking for proper place to put selected index
              for (var j=0; j < f.syntaxhl_language.options.length; j++) {
            		if (f.syntaxhl_language.options[j].value == b[1]) {
            			f.syntaxhl_language.selectedIndex = j;
            		}
            	} 
            break;			
      			case "first-line":
      			  f.syntaxhl_firstline.value = b[1];
    			  break;
      			case "fontsize":
      			  f.syntaxhl_fontsize.value = b[1];
    			  break;
      			case "gutter":
      			  if (b[1] == 'true' ) {
      			    f.syntaxhl_nogutter.checked = false;
      			  } else if (b[1] == 'false' ) {
      			    f.syntaxhl_nogutter.checked = true;
      			  } else {f.syntaxhl_nogutter.checked = false;}
    			  break;
      			case "light":
      			  if (b[1] == 'true' ) {
      			    f.syntaxhl_light.checked = true;
      			  } else if (b[1] == 'false' ) {
      			    f.syntaxhl_light.checked = false;
      			  } else {f.syntaxhl_light.checked = false;}
    			  break;
      			case "collapse":
      			  if (b[1] == 'true' ) {
      			    f.syntaxhl_collapse.checked = true;
      			  } else if (b[1] == 'false' ) {
      			    f.syntaxhl_collapse.checked = false;
      			  } else {f.syntaxhl_collapse.checked = false;}
    			  break;
      			case "highlight":
      			  f.syntaxhl_highlight.value = b[1];
      			break;
      		}        
        }     
      }
            
      // this is a total hack, but by running getContent, we force the 
      // preelementfix plugin to do it's cleanup routine
      // which fixes any <br> or tabs that may be in our pre elements.
      // The preelementfix plugin should probably do this work on the 
      // selection.getContent event as well, which would take care of it for us,
      // but it doesn't so we have to grab the whole content, which incidentally, will
      // clean all the pre's on the page, not just the one we are selecting
      // the other option is we implement the <br> conversion ourselves, but
      // I had a hell of time figuring out how to do that on just a selection
      ed.getContent();
      // FIXME, this may cause ie to modify the content when setting textarea value 
      // and screwing up whitespace
      f.syntaxhl_code.value = ed.dom.decode(this._getText(n));


    }// end if (fe.nodeName == 'PRE')
    
  },
  
  insert : function() {
    var n, f = document.forms[0], textarea_output, options = '';

    tinyMCEPopup.restoreSelection();
    n = ed.selection.getNode();

    if(f.syntaxhl_nogutter.checked) {
      options += 'gutter: false; ';
    }
    if(f.syntaxhl_light.checked) {
      options += 'light: true; ';
    }
    if(f.syntaxhl_collapse.checked) {
      options += 'collapse: true; ';
    }
    if(f.syntaxhl_fontsize.value != '') {
      var fontsize=parseInt(f.syntaxhl_fontsize.value);
      options += 'fontsize: ' + fontsize + '; ';
    }
    if(f.syntaxhl_firstline.value != '') {
      var linenumber = parseInt(f.syntaxhl_firstline.value);
      options += 'first-line: ' + linenumber + '; ';
    }
    if(f.syntaxhl_highlight.value != '') {
      options += 'highlight: [' + f.syntaxhl_highlight.value + ']; ';
    }

    if (n != null && n.nodeName == 'PRE') {
      style = 'wysiwyg-syntaxhl brush: ' + f.syntaxhl_language.value + ';' + options;
      ed.dom.setOuterHTML(n,'<pre class="' + style + '">' + ed.dom.encode(f.syntaxhl_code.value)+'</pre>');
      ed.dom.setAttrib(n,'class',style);
    } else {      
      //If no code just return.
      if(f.syntaxhl_code.value == '') {
        tinyMCEPopup.close();
        return false;
      } else {
        textarea_output = '<pre class="wysiwyg-syntaxhl brush: ';
        textarea_output += f.syntaxhl_language.value + ';' + options + '">';
        textarea_output +=  tinyMCEPopup.editor.dom.encode(f.syntaxhl_code.value);
        textarea_output += '</pre>'; 
        tinyMCEPopup.editor.execCommand('mceInsertContent', false, textarea_output);      
      }
    }
    
    tinyMCEPopup.close();

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

  
};

tinyMCEPopup.onInit.add(SyntaxHLDialog.init, SyntaxHLDialog);
