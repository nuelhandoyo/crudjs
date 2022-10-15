<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <style type="text/css">
    body {
      font-family: Arial, sans-serif;
      font-size: 14px;
      line-height: 20px;
      color: #333333;
    }

    table, th, td {
      border: solid 1px #000;
      padding: 10px;
    }

    table {
        border-collapse:collapse;
        caption-side:bottom;
    }
  </style>
  <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
        
</head>
<body>
  <h1>CRUD JS</h1>
  <table id="list-item">
      <thead>
        <tr>
          <th scope="col">Order Id</th>
          <th scope="col">Billing Name</th>
          <th scope="col">Date</th>
          <th scope="col">Total</th>
          <th scope="col">Payment Status</th>
          <th scope="col">Payment Method</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody id="list-item-body"></tbody>
  </table>
  <script type="text/javascript">
    var listItem = new Array();
    var templist = new Array();
    var itemNo = 0;
    var curr_item_no = 0;
    var newline = "\n";
    var tablid = "#list-item";
    var tablbodyid = "#list-item-body";
    var tablbodyidclear = "list-item-body";
    const tabtitle = ["Order Id", "Billing Name", "Date", "Total", "Payment Status", "Payment Method", "Actions"];


    $(document).ready(function(){
      modifying_table(0);
    });

    function taginput(tagid, tagplaceholder){
      return '<input type="text" class="form-control" id="'+tagid+'" name="'+tagid+'" placeholder="'+tagplaceholder+'">';
    }

    function taginputhidden(tagid, idx, val){
      return '<input type="hidden" id="'+tagid+''+idx+'" name="'+tagid+''+idx+'" value="'+val+'">';
    }

    function tagtdx(tagdatatitle){
      return '<td data-title="'+tagdatatitle+'">';
    }

    function validation(status){
      var alertString = "";
      templist = new Array();
      for (var i = 0; i < tabtitle.length-1; i++) {
        var tagid = tabtitle[i].toLowerCase().split(" ").join("");
        var xyz = document.getElementById(tagid).value;
        var c=0;
        if(xyz.trim()==''){
          alertString += "Please fill "+tabtitle[i]+newline;
          c++;
        }
        if(c==0){ 
          var dataxyz = {[tagid] : xyz};
          templist = templist.concat(dataxyz); 

        }
      }
      
      if(alertString != ""){
        alert(alertString);
      }else{
        if(status==1){
          if(listItem.length==0){
            modifying_table(status);
          }else{
            var countitem=0;
            for(var i=0;i<listItem.length;i++){
              if(listItem[i][1] == templist[0]){
                countitem++;
                break;
              }
            }

            if(countitem>0){
              alert('Data sudah terdaftar');
              // $('#myItemModal').modal('hide');
            }else{
              modifying_table(status);
            }
          }
        }else{
          modifying_table(status);
        }
      }
      
    }

    function modifying_table(status){
      var $tableMaster = $(tablid);
      $(tablbodyid).remove();
      var $newBody = $('<tbody id="'+tablbodyidclear+'"></tbody>').appendTo($tableMaster);
      var html = '';
      var $tableMasterBody = $(tablbodyid);
      var tagtdy = '</td>';
      var divx = '<div class="btn-group">';
      var divy = '</div>';
      
      var valabc = new Array();
      
      if(status!=0){
        valabc = templist;

      }

      
      if(status==1){
        tempItem = new Array();
        var dataabc = {"itemNo" : itemNo};
        tempItem = tempItem.concat(dataabc,valabc); 
        listtemporary = new Array(tempItem);
        listItem = listItem.concat(listtemporary);
      }

      if(listItem.length>0){
        
        for (var i = 0; i < listItem.length; i++) {
          html = '';
          var obj = listItem[i];
          if(status==0){
            Object.values(obj[0])=i;
          }else if(status==2){
            if (listItem[i][0]['itemNo'] == curr_item_no) { 
              for (var ix = 0; ix < tabtitle.length-1; ix++) {
                var tagid = tabtitle[ix].toLowerCase().split(" ").join("");
                jx = ix+1;
                listItem[i][jx][tagid] = templist[ix][tagid];
              }    
            }
          }else{
            obj = templist; 
          }
          
          for (var j = 0; j < tabtitle.length-1; j++) {
            var tagid = tabtitle[j].toLowerCase().split(" ").join("");
            jidx = j+1;
            html += tagtdx(tabtitle[j])+listItem[i][jidx][tagid] + taginputhidden(tagid,i, listItem[i][jidx][tagid]);
            if(j==0){
              html +=  taginputhidden("listItem[]",'', i);
            }
            html += tagtdy;
          }
          html += tagtdx("Actions")+divx;
          html += '<button type="button" id="item_edit" name="item_edit" value="item_edit" class="btn btn-success" onclick="editItemList('+i+')">Edit</button>';
          html += '<button type="button" id="item_remove'+i+'" name="item_remove'+i+'" value="item_remove" class="btn btn-dark" onclick="removeItemList('+i+')">Delete</i></button>';
          html += '</div>';
          html += divy+tagtdy;
          var $newRow = $('<tr id=list'+i+'></tr>').append(html).appendTo($tableMasterBody);
        }  
        
      }
    
      html ='';
      for (var k = 0; k < tabtitle.length; k++) {
        var tagtitle = tabtitle[k];
        var tagid = tagtitle.toLowerCase().split(" ").join("");

        if(tabtitle.length!= k+1){
          html += tagtdx(tagtitle)+taginput(tagid,tagtitle)+tagtdy; 
        }else{
          html += tagtdx(tagtitle)+divx;
          html += '<button id="addItemButton" type="button" class="btn btn-primary" onClick="validation(1)">Add</button>';
          html += '<button id="editItemButton" type="button" class="btn btn-success" onClick="validation(2)" style="display:none;">Save</button>';
          html += '<button type="button" class="btn btn-dark" onClick="clearInputItem()">Clear </button>';
          html += divy+tagtdy;
        }
      }
      
      $newRow = $('<tr></tr>').append(html).appendTo($tableMasterBody); 
      
      if(status==1){
        itemNo++;
      }else{
        curr_item_no = 0;
      }
    }

    function switchRemoveButton(status){
      for (var i = 0; i < listItem.length; i++) {
        var object = listItem[i];
        if(status==0){
          document.getElementById('item_remove'+Object.values(object[0])).disabled = true; 
        }else{
          document.getElementById('item_remove'+Object.values(object[0])).disabled = false; 
        }
      }
    }


    function removeItemList(itemNo) {
      $('#list'+itemNo).remove();
      for (var i = 0; i < listItem.length; i++) {
        var object = listItem[i];
        if (object.itemNo == itemNo) {
          listItem.splice(i,1); 
          break;
        }
      }
    }

    function clearInputItem(){
      curr_item_no = 0;
      for (var i = 0; i < tabtitle.length-1; i++) {
        var tagid = tabtitle[i].toLowerCase().split(" ").join("");
        document.getElementById(tagid).value = "";
      }
      document.getElementById('addItemButton').style.display = "inline";
      document.getElementById('editItemButton').style.display = "none";
      switchRemoveButton(1);
    }

    function editItemList(itemNo){
      clearInputItem();
      curr_item_no = itemNo;
      for (var i = 0; i < tabtitle.length-1; i++) {
        var tagid = tabtitle[i].toLowerCase().split(" ").join("");
        document.getElementById(tagid).value = document.getElementById(tagid+itemNo).value;
      }
      document.getElementById('addItemButton').style.display = "none";
      document.getElementById('editItemButton').style.display = "inline";
      switchRemoveButton(0);
    }
  </script>
</body>
</html>
