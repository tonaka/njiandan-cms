function time_now() {
    today=new Date();
    return today.getTime();
}

/* show item */
var the_timeout_dbh = null;

function showItem(item) {
  document.getElementById(item).style.display='';
}

/* hide item */
function hideItem(item) {
  document.getElementById(item).style.display='none';
}

function clean_checked(item) {
  document.getElementById(item).checked = '';	
}

function category_delete(item, category_name, category_id) {
	document.getElementById('delete_category_name').innerHTML = category_name;
	document.getElementsByName('action')[0].value = 'delete_category';
	document.getElementsByName('delete_category_id')[0].value = category_id;
	showItem(item);
}

function g_e(o) {
	return document.getElementById(o);
}

function set_readbypassword(oValue) {
	Oreadbypassword = g_e('readbypassword');
	if (oValue) {
		Oreadbypassword.style.display = '';
	} else {
		Oreadbypassword.style.display = 'none';
	}
	
	
}

function hidden_readbypassword() {
	Oreadbypassword = g_e('readbypassword');
	Oreadbypassword.style.display = 'none';
}

function show_noticemessage(notice_message) {
	document.getElementById('noticemessage_content').innerHTML = notice_message;
	showItem('noticemessage');
	if(the_timeout_dbh) {
		clearTimeout(the_timeout_dbh);
	}
	the_timeout_dbh = setTimeout("hideItem('noticemessage')", 15000);	
}

function delete_posts() {
	oPosts = document.getElementsByName(oName);
	oPosts_counts = oPosts.length;
	if(oPosts_counts) {
		var c = 0;
		for(i = 0; i < oPosts_counts; i++) {
			if(oPosts[i].checked) {
				Tr_Id = 'tr_' + oPosts[i].value;
				document.getElementById(Tr_Id).style.display = 'none';
				oPosts[i].checked = '';
				c = c + 1;
			}
		}
	}
	if(c) {
		notice_message = c + ' conversations have been moved to the Trash.'
	} else {
		notice_message = 'No conversations selected.';
	}
	show_noticemessage(notice_message);
}


function mod_allcheckbox_status2( oName ,iValue ){

	if(iValue) {
		$oInputs = document.getElementsByName(oName);
		$allcheckbox_counts = $oInputs.length;
		for($i = 0; $i < $allcheckbox_counts; $i++) {
			$oInputs[$i].checked = 'checked';
		}
	} else {
		$oInputs = document.getElementsByName(oName);
		$allcheckbox_counts = $oInputs.length;
		for($i = 0; $i < $allcheckbox_counts; $i++) {
			$oInputs[$i].checked = '';
		}
	}
}

function change_allcheckbox_status(oName, iDall) {
	oPosts = document.getElementsByName(oName);
	oPosts_counts = oPosts.length;
	var c = 0;
	if(oPosts_counts) {
		for(i = 0; i < oPosts_counts; i++) {
			if( oPosts[i].checked )
			{
				c = c + 1;
			}
		}
	}
	if( c==oPosts_counts && c!=1)
	{
		g_e(iDall).checked='checked';
	}else
	{
		g_e(iDall).checked='';
	}
}

function change_star_img( star_status, post_id  ) {
	oStar = g_e( 'star_img_' + post_id );
	if( star_status == 1)
		oStar.src="img/star_on.gif";
	else
		oStar.src="img/star_off.gif";
}

function clear_input_value( oObject ) {
	oInput = g_e( oObject );
	oInput.value = '';
}

function set_input_value( oObject , sValue ) {
	oInput = g_e( oObject );
	oInput.value = sValue;
}

var Image_array = Array();
Image_array['JPG'] = true;
Image_array['PNG'] = true;
Image_array['GIF'] = true;
function InsertSerlectContentToEditor(file_path, content_type) {
	var image_width = 450;
	switch( content_type ) {
		case 1:
			content_value = '<img src="' + file_path + '" width="' + image_width + '" align="left" >';
			break;
		case 2:
			content_value = '<img src="' + file_path + '" width="' + image_width + '" align="right" >';
			break;
		case 3:
			content_value = '<p style="text-align: center"><img src="' + file_path + '" width="' + image_width + '" ></p>';
			break;
		case 4:
			var file_list = $("#fileList:eq(0)").val();
			if (file_list == null) {
				return false;
			}

			file_list = '\'' + file_list + '\'';
			var arrfiles = file_list.split(",");
			var file_count = arrfiles.length;
			if(file_count < 2) {
				alert('请按住Ctrl键,然后选取2个图片文件');
				return false;
			} else if (file_count > 2) {
				alert('该模式每次只能选取2个图片文件');
				return false;
			}
			var file_lists = '';
			var aCommon = '';
			var arrfile = '';

			arrfile0 = arrfiles[0].split("|");
			var file_0_extra = arrfile0[1].toUpperCase();

			arrfile1 = arrfiles[1].split("|");
			var file_1_extra = arrfile1[1].toUpperCase();

			if (Image_array[file_0_extra] == undefined || Image_array[file_1_extra] == undefined) {
				alert('只允许使用图片');
				return false;
			}

			content_value = '<div style="text-align: center;"><table border="0">' +
					'<tbody><tr>' +
					'<td><img src="/ttc/attach/1/1079916944.jpg" alt="用户插入图片" style="cursor: pointer;" onclick="open_img(\'/ttc/attach/1/1079916944.jpg\')" height="133" width="250"></td>' +
					'<td><img src="/ttc/attach/1/1249871739.png" alt="用户插入图片" style="cursor: pointer;" onclick="open_img(\'/ttc/attach/1/1249871739.png\')" height="156" width="250"></td>' +
					'</tr></tbody></table></div>';
			break;
		case 'down_load_file':
			content_value = '<a href="' + file_path + '"' + '下载文件'

	}

	var win = window.opener ? window.opener : window.dialogArguments;
	if (!win) {
		win = top;
	}
	tinyMCE = win.tinyMCE;
	tinyMCE.selectedInstance.getWin().focus();
	tinyMCE.execCommand( 'mceInsertContent', false, content_value );

}

function add_download_link(file_url) {
	tinyMCE.execCommand('mceReplaceContent', false, '<a href="' + file_url + '">{$selection}</a>');
}

function edInsertContent(myField, myValue) {
	//IE support
	if (document.selection) {
		myField.focus();
		sel = document.selection.createRange();
		sel.text = myValue;
		myField.focus();
	}
	//MOZILLA/NETSCAPE support
	else if (myField.selectionStart || myField.selectionStart == '0') {
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		myField.value = myField.value.substring(0, startPos)
		              + myValue 
                      + myField.value.substring(endPos, myField.value.length);
		myField.focus();
		myField.selectionStart = startPos + myValue.length;
		myField.selectionEnd = startPos + myValue.length;
	} else {
		myField.value += myValue;
		myField.focus();
	}
}
