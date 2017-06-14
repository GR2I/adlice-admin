<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->

<?php 
  require_once(__DIR__."/src/config.php");
  require_once(__DIR__."/src/lib/usercake/init.php");
  if (!UCUser::CanUserAccessUrl($_SERVER['PHP_SELF'])){die();}
  $user = UCUser::getCurrentUser();
?> 

<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $user_settings->WebsiteName() ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="plugins/ionicons/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables/css/dataTables.bootstrap.css">
  <link rel="stylesheet" href="plugins/datatables/extensions/Buttons/css/buttons.bootstrap.min.css">
  <link rel="stylesheet" href="plugins/datatables/extensions/Select/css/select.bootstrap.min.css">
  <link rel="stylesheet" href="plugins/datatables/extensions/Editor/css/editor.bootstrap.min.css">
  <link rel="stylesheet" href="plugins/datatables/extensions/Responsive/css/responsive.bootstrap.min.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
  -->
  <link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
  <style type="text/css">
  	body .modal-dialog {
	    width: 60%;
	}
	
	.tab-content {
	    border-left: 1px solid #ddd;
	    border-right: 1px solid #ddd;
	    border-bottom: 1px solid #ddd;
	    padding: 10px;
	}
	
	.nav-tabs {
	    margin-bottom: 0;
	}
  </style>
  
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php  include(__DIR__."/top-nav.php"); ?> 
  <?php  include(__DIR__."/left-nav.php"); ?> 
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">      
      <div id='content'>                 
        <!-- Horizontal Form -->
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">RogueKiller Agents</h3>
          </div>             
          <div class="box-body">
            <table id="agents" class="table table-bordered table-striped dt-responsive" width="100%" cellspacing="0">
              <thead>
              <tr>
                <th>Id</th>
                <th>IP (v4)</th>
                <th>Operating System</th>                
                <th>Version</th>
                <th>Status</th>
                <th>Last Seen</th>
                <th>Actions</th>  
              </tr>
              </thead>
              <tbody>              
              </tbody>
            </table>
          </div>
          <!-- /.box-body -->          
        </div>          
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php  include(__DIR__."/footer.php"); ?> 
  <?php  include(__DIR__."/right-nav.php"); ?> 
  
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- TinyMCE -->
<script src="plugins/tinymce/js/tinymce/tinymce.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/js/jquery.dataTables.js"></script>
<script src="plugins/datatables/js/dataTables.bootstrap.js"></script>
<script src="plugins/datatables/extensions/Buttons/js/dataTables.buttons.js"></script>
<script src="plugins/datatables/extensions/Buttons/js/buttons.bootstrap.js"></script>
<script src="plugins/datatables/extensions/Select/js/dataTables.select.js"></script>
<script src="plugins/datatables/extensions/Editor/js/dataTables.editor.js"></script>
<script src="plugins/datatables/extensions/Editor/js/editor.bootstrap.js"></script>
<script src="plugins/datatables/extensions/Editor/js/editor.tinymce.js"></script>
<script src="plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables/extensions/Responsive/js/responsive.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- Select2 -->
<!--<script src="plugins/select2/select2.full.min.js"></script>-->
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
 
<script>
function start_scan(id)
{
	$.ajax({
		url: 'api.php?api=start_scan',
		type: 'post',	
        dataType: "json",
        data: {id: id},				
		success: function(data){			
  			table.ajax.reload();		
		},
		cache: false
	});
}

function start_update(id)
{
	$.ajax({
		url: 'api.php?api=start_update',
		type: 'post',	
        dataType: "json",
        data: {id: id},				
		success: function(data){			
  			table.ajax.reload();		
		},
		cache: false
	});
}
</script>
     
<script>
  var table;
  
  $(function () { 
    table = $('#agents').DataTable({
      dom: "Bfrtip",
      paging: true,
      lengthChange: true,
      searching: true,
      ordering: true,
      info: true,
      autoWidth: true,
      processing: false,
      serverSide: false,
      responsive: true,
      ajax: {
        url: "api.php?api=get_agents"   
      },      
      columns: [
		{ data: "id", width: "20%" },
		{ data: "ipv4", width: "15%" },
		{ data: "os", width: "20%" },
		{ 
			data: "version", 
			width: "10%",
			render: function (data, type, row) {
			  if (row.is_outdated > 0) {
				  return "<span class='label label-danger' data-toggle='tooltip' title='New version: " + row.version_available + "'>" + data + "</span>";
			  }              
			  else {
                  return "<span class='label label-success' data-toggle='tooltip' title='Up-to-date'>" + data + "</span>";
              }
          }
		},
        { 
          data: "status", 
          width: "10%",
          render: function (data, type, row) {
			  // Check if offline
			  var date = new Date(row.last_seen);
			  if (((new Date) - date) > (60 * 1000)) {
				  return "<span class='label label-default'>Offline</span>";
			  }              
			  else if (data == "Ready" || data == "Online") {
                  return "<span class='label label-success'>" + data + "</span>";
              } else {
                  return "<span class='label label-primary'>" + data + "</span>";
              }
          }
        },
        { data: "last_seen", width: "15%" },    
        { 
            data: "id",
            width: "10%", 
            render: function (data, type, row) 
	    	{
    	    	// Check if offline
    	    	var date = new Date(row.last_seen);
            	if (((new Date) - date) > (60 * 1000)) {
					return "";
				}
    	    	
	    		var content = "<div class='btn-group'>"
				+ "<button type='button' class='btn btn-sm btn-default'>Actions</button>"
				+ "<button type='button' class='btn btn-sm btn-default dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>"
				+ "  <span class='caret'></span>"
				+ "  <span class='sr-only'>Toggle Dropdown</span>"
				+ "</button>"
	    		+ "<ul class='dropdown-menu'>"
	    		if (row.status == "Ready") {
				content = content
	    		+ "  <li>"
			    + "    <a href='#' style='color:black;' class='menu-button-download' OnClick='start_scan(\"" + row.id + "\")'>"
				+ "      <i class='glyphicon glyphicon-search'></i>"
				+ "      <span>Start Scan</span>"
				+ "    </a>"
				+ "  </li>";
	    		}
				if (row.is_outdated > 0) {
				content = content
				+ "  <li>"
			    + "    <a href='#' style='color:black;' class='menu-button-update' OnClick='start_update(\"" + row.id + "\")'>"
				+ "      <i class='glyphicon glyphicon-refresh'></i>"
				+ "      <span>Start Update</span>"
				+ "    </a>"
				+ "  </li>";
				}			
				content = content
				+ "</ul>"
				+ "</div>";
				return content;
	        }
        }
      ],
      order: [[ 5, "desc" ]],
      select: true,
      buttons: [
      ],
    });

    setInterval( function () {
        table.ajax.reload(null, false);
    }, 10000 );    
  });
</script>
</body>
</html>