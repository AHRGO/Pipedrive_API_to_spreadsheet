<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= explode('|', $_REQUEST['prods'])[2] ?></title>
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"><link>
   <link rel="stylesheet" href="datatables/datatables.min.css">
   <style>
        body{
            margin-left:3vw;
            margin-right:3vw;

        }
        .btn{
            margin-left:1vw;
        }
    </style>
</head>
<body>
<div id="logo" class="row">
    <div class="col-md-12">
        <a href="index.php"><img src="LOGO-GRECON.png" alt="Logo Grecon" width="400"></a>
    </div>
</div>

<?php

const API_TOKEN = 'insert_here_you_pipedrive_api_token';
const COMPANY_DOMAIN = 'insert_here_your_company_domain';
const DEALS_PER_PAGE = 500;
//die(var_dump($_REQUEST));


function getDeals($limit = DEALS_PER_PAGE, $start = 0) {
    
    $url = 'https://' . COMPANY_DOMAIN . '.pipedrive.com/api/v1/products/'.explode('|', $_REQUEST['prods'])[0].'/deals?api_token='. API_TOKEN . '&start=' . $start . '&limit=' . $limit;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, false); 
    $output = curl_exec($ch);
    curl_close($ch);

    if (empty($output)){
        echo "<h2>You had an error in the connection with the API.</h2><br>";
        die;
    }
   
    $result = json_decode($output, true);
    $deals = [];
    
    if (!empty($result['data'])) {
        foreach ($result['data'] as $deal) {
            $title = str_replace('"', '\"',$deal["title"]);
            $deals[] = array("ID: " => $deal["id"], "Title: " => $title, "Creation Date: " => $deal["add_time"], "Status: " => $deal["status"], "Product Code: " => explode('|', $_REQUEST['prods'])[1]);
        }
    } else {
       echo "<h2>Oops, it seems that there's nothing here, please come back.</h2><br>";
       echo "<h6><i>Click in the logo to go back.</i></h6>";
       die;
       // print_r($result);
    }
 
    if (!empty($result['additional_data']['pagination']['more_items_in_collection'] 
        && $result['additional_data']['pagination']['more_items_in_collection'] === true)) {
        
        $deals = array_merge($deals, getDeals($limit, $result['additional_data']['pagination']['next_start'])); 
        
    }
   
    return $deals;
}
 

$deals = getDeals();

//echo json_encode($deals);
//die;

?>



    <br>
    <div class="row">
        <div class="col-md-2">
            <h3>Deals Spreadsheet</h3>
        </div>
        <div class="col-md-1">
            <a href="index.php"><button class="btn btn-light" type="submit" >Back</button></a>
        </div>
    </div>
    
    <br>
    <div id="sheet">
    </div>
    <br>

 
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="datatables/datatables.min.js"></script>
<script src="datatables/jszip.min.js"></script>
<script src="datatables/dataTables.buttons.min.js"></script>
<script src="datatables/buttons.html5.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
   $(document).ready(function() {
      var data = `<?php echo json_encode($deals);?>`;
      data = JSON.parse(data);

      fill_tab("sheet", "Deals by products", "table_sheet", data);
   });

   function fill_tab(div, titulo, tab, itens) {
        if(itens.lenght == 0)
            return;
        if (div.length > 0)
        $("#" + div).append("<h6>" + titulo + "</h6><table id='" + tab + "' class='table table-striped table-bordered full'></table><br>");
        var column_set = [];
        var data_set = [];
        var tab_1 = null;
        
        
        Object.keys(itens[0]).forEach(function(key) {
            column_set.push({
                'title': key
            });
        })

        var tab_1 = $("#" + tab).DataTable({
            "columns": column_set,
            "dom": '<"row alin" B i f>t',
            "buttons": [
                    {
                        text: 'Excel Download',
                        extend: 'excel',
                        titleAttr: 'Export XLS'
                      
                    }
                ],
            "paging": false,
            "searching": false,
            "info": false,
            "ordering": false
        });

        var data_set = {};
        itens.forEach(function(item){
            data_set = [];
        
            Object.values(item).forEach(function(val){
                data_set.push(val);
            });
            tab_1.row.add(data_set).draw();

        });


        return tab_1;
    }
    
</script>
</body>
</html>



