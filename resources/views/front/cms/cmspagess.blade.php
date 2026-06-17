<html>
<head>
    <title>The Zouple Invoice</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--//////////////  bootstrap ///////////-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- ///  font awesome //////-->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--//////////   external css //////////////-->
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/magiczoomplus.css">
    <!--/////////////////   font /////////-->
    <!--<link href="https://fonts.googleapis.com/css?family=Lobster&display=swap" rel="stylesheet">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="icon" href="{{URL::asset('public/img/dark-logo.png')}}" type="image/gif" sizes="16x16">
    
</head>
<body>
    <section>
            <!--//////////////////////////   content ///////////////////////-->
            <section class="border border-dark  my-3">
                <div class="container py-2 px-5">
                    <div class="row">
                        <div class="col-12 h1 text-center pt-4">
                            <img src="{{URL::asset('public/img/dark-logo.png')}}" width="200px" alt="logo">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 align-self-center">
                            @foreach($cms_data as $data)
                            <?php echo $data->description; ?>
                            @endforeach
                        </div>
                    
                    </div>
                    
                    <div class="row justify-content-center mt-4">
                        <div class="col-sm-4 text-center">
                        
                        <button onclick="myFunction()" class="btn text-white" style="background-color:#000000">Print this page</button>
                        
                        <script>
                        function myFunction() {
                          window.print();
                        }
                        </script>                        
                        </div>
                    </div>
                </div>
            </section>
    </section>   
</body>
    <!--/////////////////////  script src ////////////////////-->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script  src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <!--//////////////   external js ////////////////-->
</html>
        
        