/**
 * $Id $
 * Moved from tiki-js.js for Tiki 3.0 to make way for JQuery version
 */

 // --- begin of sorttable, written by Stuart Langridge, November 2003, MIT license ---
addEvent(window, "load", sortables_init);

var SORT_COLUMN_INDEX;

function sortables_init() {
    // Find all tables with class sortable and make them sortable
    if (!document.getElementsByTagName) return;
    tbls = document.getElementsByTagName("table");
    for (ti=0;ti<tbls.length;ti++) {
        thisTbl = tbls[ti];
        if (((' '+thisTbl.className+' ').indexOf("sortable") != -1) && (thisTbl.id)) {
            //initTable(thisTbl.id);
            ts_makeSortable(thisTbl);
        }
    }
}

function ts_makeSortable(table) {
    if (table.rows && table.rows.length > 0) {
        var firstRow = table.rows[0];
    }
    if (!firstRow) return;

    // We have a first row: assume it's the header, and make its contents clickable links
    for (var i=0;i<firstRow.cells.length;i++) {
        var cell = firstRow.cells[i];
        var txt = ts_getInnerText(cell);
        cell.innerHTML = '<a href="#" class="sortheader" onclick="ts_resortTable(this);return false;">'+txt+'<span class="sortarrow">&nbsp;&nbsp;&nbsp;</span></a>';
    }
}

function ts_getInnerText(el) {
  if (typeof el == "string") return el;
  if (typeof el == "undefined") { return el };
  if (el.innerText) return el.innerText;	//Not needed but it is faster
  var str = "";

  var cs = el.childNodes;
  var l = cs.length;
  for (var i = 0; i < l; i++) {
    switch (cs[i].nodeType) {
      case 1: //ELEMENT_NODE
        str += ts_getInnerText(cs[i]);
        break;
      case 3:	//TEXT_NODE
        str += cs[i].nodeValue;
        break;
    }
  }
  return str;
}

function ts_resortTable(lnk) {
    // get the span
    var span;
    for (var ci=0;ci<lnk.childNodes.length;ci++) {
        if (lnk.childNodes[ci].tagName && lnk.childNodes[ci].tagName.toLowerCase() == 'span') span = lnk.childNodes[ci];
    }
    var spantext = ts_getInnerText(span);
    var td = lnk.parentNode;
    var column = td.cellIndex;
    var table = getParent(td,'TABLE');

    // Work out a type for the column
    if (table.rows.length <= 1) return;
    var itm = ts_getInnerText(table.rows[1].cells[column]);
    sortfn = ts_sort_caseinsensitive;
    if (itm.match(/^\d\d[\/-]\d\d[\/-]\d\d\d\d$/)) sortfn = ts_sort_date;
    if (itm.match(/^\d\d[\/-]\d\d[\/-]\d\d$/)) sortfn = ts_sort_date;
    if (itm.match(/^[$]/)) sortfn = ts_sort_currency;
    if (itm.match(/^[\d\.]+$/)) sortfn = ts_sort_numeric;
    SORT_COLUMN_INDEX = column;
    var firstRow = new Array();
    var newRows = new Array();
    for (i=0;i<table.rows[0].length;i++) { firstRow[i] = table.rows[0][i]; }
    for (j=1;j<table.rows.length;j++) { newRows[j-1] = table.rows[j]; }

    newRows.sort(sortfn);

    if (span.getAttribute("sortdir") == 'down') {
        ARROW = '&nbsp;&nbsp;<img src="pics/icons/resultset_up.png" border="0" width="16" height="16" />';
        newRows.reverse();
        span.setAttribute('sortdir','up');
    } else {
        ARROW = '&nbsp;&nbsp;<img src="pics/icons/resultset_down.png" border="0" width="16" height="16" />';
        span.setAttribute('sortdir','down');
    }

    // We appendChild rows that already exist to the tbody, so it moves them rather than creating new ones
    // don't do sortbottom rows
    for (i=0;i<newRows.length;i++) { if (!newRows[i].className || (newRows[i].className && (newRows[i].className.indexOf('sortbottom') == -1))) table.tBodies[0].appendChild(newRows[i]);}
    // do sortbottom rows only
    for (i=0;i<newRows.length;i++) { if (newRows[i].className && (newRows[i].className.indexOf('sortbottom') != -1)) table.tBodies[0].appendChild(newRows[i]);}

    // Delete any other arrows there may be showing
    var allspans = document.getElementsByTagName("span");
    for (var ci=0;ci<allspans.length;ci++) {
        if (allspans[ci].className == 'sortarrow') {
            if (getParent(allspans[ci],"table") == getParent(lnk,"table")) { // in the same table as us?
                allspans[ci].innerHTML = '&nbsp;&nbsp;&nbsp;';
            }
        }
    }

    span.innerHTML = ARROW;
}

function getParent(el, pTagName) {
  if (el == null) return null;
  else if (el.nodeType == 1 && el.tagName.toLowerCase() == pTagName.toLowerCase())	// Gecko bug, supposed to be uppercase
    return el;
  else
    return getParent(el.parentNode, pTagName);
}
function ts_sort_date(a,b) {
    // y2k notes: two digit years less than 50 are treated as 20XX, greater than 50 are treated as 19XX
    aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]);
    bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]);
    if (aa.length == 10) {
        dt1 = aa.substr(6,4)+aa.substr(3,2)+aa.substr(0,2);
    } else {
        yr = aa.substr(6,2);
        if (parseInt(yr) < 50) { yr = '20'+yr; } else { yr = '19'+yr; }
        dt1 = yr+aa.substr(3,2)+aa.substr(0,2);
    }
    if (bb.length == 10) {
        dt2 = bb.substr(6,4)+bb.substr(3,2)+bb.substr(0,2);
    } else {
        yr = bb.substr(6,2);
        if (parseInt(yr) < 50) { yr = '20'+yr; } else { yr = '19'+yr; }
        dt2 = yr+bb.substr(3,2)+bb.substr(0,2);
    }
    if (dt1==dt2) return 0;
    if (dt1<dt2) return -1;
    return 1;
}

function ts_sort_currency(a,b) {
    aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g,'');
    bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g,'');
    return parseFloat(aa) - parseFloat(bb);
}

function ts_sort_numeric(a,b) {
    aa = parseFloat(ts_getInnerText(a.cells[SORT_COLUMN_INDEX]));
    if (isNaN(aa)) aa = 0;
    bb = parseFloat(ts_getInnerText(b.cells[SORT_COLUMN_INDEX]));
    if (isNaN(bb)) bb = 0;
    return aa-bb;
}

function ts_sort_caseinsensitive(a,b) {
    aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).toLowerCase();
    bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).toLowerCase();
    if (aa==bb) return 0;
    if (aa<bb) return -1;
    return 1;
}

function ts_sort_default(a,b) {
    aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]);
    bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]);
    if (aa==bb) return 0;
    if (aa<bb) return -1;
    return 1;
}

// function to allow multiselection in checkboxes
// must be called like this :
//
// <input type="checkbox" onclick="checkbox_list_check_all(form_name,[checkbox_name_1,checkbox_name2 ...],true|false);">
function checkbox_list_check_all(form,list,checking) {
  for (var checkbox in list) {
    document.forms[form].elements[list[checkbox]].checked=checking;
  }
}


function addEvent(elm, evType, fn, useCapture)
// addEvent and removeEvent
// cross-browser event handling for IE5+,  NS6 and Mozilla
// By Scott Andrew
{
  if (elm.addEventListener){
    elm.addEventListener(evType, fn, useCapture);
    return true;
  } else if (elm.attachEvent){
    var r = elm.attachEvent("on"+evType, fn);
    return r;
  } else {
 	return false;	//formerly: alert("Handler could not be removed");
  }
}

function adjustThumbnails() {
  var i,j,h = 0;
  var t = document.getElementById("thumbnails").childNodes;
  for ( i = 0; i < t.length; i++ ) {
    if ( t[i].className == "thumbnailcontener" ) {
      var t2 = t[i].childNodes;
      for ( j = 0; j < t2.length; j++ ) {
        if ( t2[j].className == "thumbnail" ) {
          t2[j].style.height = "100%";
          t2[j].style.overflow = "visible";
        }
      }
      if ( t[i].offsetHeight >= h ) {
        h = t[i].offsetHeight;
        t[i].style.height = h+"px";
      } else if ( t[i].offsetHeight < h ) {
        t[i].style.height = h+"px";
      }
    }
  }
  for ( i = 0; i < t.length; i++ ) {
    if ( t[i].className == "thumbnailcontener" ) {
      if ( t[i].offsetHeight <= h ) {
        t[i].style.height = h+"px";
      } else {
        break;
      }
    }
  }
}

// --- end of sorttable ---