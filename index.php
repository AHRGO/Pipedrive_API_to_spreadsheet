<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose your product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"><link>
    <link rel="stylesheet" href="datatables/datatables.min.css">
    <style>
        body{
            margin-left:3vw;
            margin-right:3vw;

        }
    </style>
</head>
<body>
    <div id="logo" class="row">
        <div class="col-md-12">
            <img src="LOGO-GRECON.png" alt="Logo Grecon" width="400">
        </div>
    </div>
    <br>
    <h4>Choose a product: </h4>
   
    <?php
       
        $api_token = 'insert_here_you_pipedrive_api_token';
        $company_domain = 'insert_here_your_company_domain';
        $limit = 100;

        $url_prod = 'https://'.$company_domain.'.pipedrive.com/api/v1/products?&limit='.$limit.'&api_token='.$api_token;
        $ch_prod = curl_init();
        curl_setopt($ch_prod, CURLOPT_URL, $url_prod);
        curl_setopt($ch_prod, CURLOPT_RETURNTRANSFER, true);
        $output2 = curl_exec($ch_prod);
        curl_close($ch_prod);
        $result_prod = json_decode($output2, true); 
    ?>
    
    <form action="pagination.php">
        <div class="row">
            <div class="col-md-5">
                <select name="prods" id="prods" class="form-control">
                    <?php 
                        foreach ($result_prod['data'] as $key2 => $prod) {
                        $ids_p[$key2] = $prod['id'];
                        $code_p[$key2] = $prod['code']; 
                    ?>
                    <option value="<?php echo $ids_p[$key2].'|'.$code_p[$key2].'|'.$prod['name'];?>"><?php echo $ids_p[$key2].' '.' '.$prod['name'] ; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-4">
                <input class="btn btn-light" type="submit" value="Get Deals"/>
            </div>           
        </div>
    </form>
              
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>


</body>
</html>





