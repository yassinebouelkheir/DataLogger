<?php
    if(isset($_GET["value"]) && isset($_GET["page"]))
    {
      $val = $_GET["value"];
      $page = $_GET["page"];
      $page += $val;
      if($page < 54) $page = 62;
      if($page > 62) $page = 54;
    }
    else
    {
      $page = 54;
    }
    $mysqli = new mysqli("localhost", "root", "", "PFE");
    if($page == 60) $query = 'SELECT * FROM `SENSORS_STATIC` WHERE ID = 58 LIMIT 1';
    else if($page == 61) $query = 'SELECT * FROM `SENSORS_STATIC` WHERE ID = 55 LIMIT 1';
    else if($page == 62) $query = 'SELECT * FROM `SENSORS_STATIC` WHERE ID = 56 LIMIT 1';
    else $query = 'SELECT * FROM `SENSORS_STATIC` WHERE ID = '.$page.' LIMIT 1';

    $result = $mysqli->query($query);
    $rows = array();
    while($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
    $result->free();

    if($page == 60) $rows[0]['VALUE'] = number_format(((pow((($rows[0]['VALUE']*1023)/100),2)/10)/(50)), 1);
    if($page == 61) $rows[0]['VALUE'] = number_format(($rows[0]['VALUE']*220), 1);
    if($page == 62){
      $query = 'SELECT * FROM `SENSORS_STATIC` WHERE ID = 54 LIMIT 1';
      $result = $mysqli->query($query);
      $rows1 = array();
      while($row = $result->fetch_assoc()) {
        $rows1[] = $row;
      }
      $result->free();
      $rows[0]['VALUE'] = ($rows[0]['VALUE']*$rows1[0]['VALUE']);
    }

    $query = 'SELECT * FROM `CHARGES` WHERE 1 ORDER BY `ID` ASC';
    $result = $mysqli->query($query) or die($mysqli->error);
    $chargesrows = array();
    while($row = $result->fetch_assoc()) {
      $chargesrows[] = $row;
    }
    $result->free();
    $mysqli->close();
?>
<!DOCTYPE html>
<html>
  <head>
      <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
      <meta name="author" content="BOUELKHEIR Yassine">
      <!-- <meta http-equiv="refresh" content="90"> -->
      <title>RPi - Data Logger v1.0</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      <script src="dist/chart.js"></script>
  </head>
  <body style="background-image:url(/assets/images/login.png); background-position: top; background-size: cover;">
    <div class="container" style="background: rgba(255,255,255,0.7);">
        <h1 class="page-header text-center">ESTS - Data Logger V1.0</h1>
        <div class="row">
            <div class="col-md-3">
                <h3 class="page-header text-center"><?php 
                            switch($page)
                            {
                              case 54:{
                                echo 'Courant DC';
                                break;
                              }
                              case 55:{
                                echo 'Courant AC';
                                break;
                              }
                              case 56:{
                                echo 'Tension DC';
                                break;
                              }
                              case 57:{
                                echo 'Température';
                                break;
                              }
                              case 58:{
                                echo 'Luminosité';
                                break;
                              }
                              case 59:{
                                echo 'Humidité';
                                break;
                              }
                              case 60:{
                                echo 'Irradiation';
                                break;
                              }
                              case 61:{
                                echo 'Puissance AC';
                                break;
                              }
                              case 62:{
                                echo 'Puissance DC';
                                break;
                              }
                            }
                          ?></h3>
                
                    <div class="form-group text-center">
                        <h4>Valeur en temps réel : <h1 class="text-center text-primary" id="updateVal"> 
                          <?php 
                            echo $rows[0]['VALUE'];
                            switch($page)
                            {
                              case 54:{
                                echo ' A';
                                break;
                              }
                              case 55:{
                                echo ' A';
                                break;
                              }
                              case 56:{
                                echo ' V';
                                break;
                              }
                              case 57:{
                                echo ' °C';
                                break;
                              }
                              case 58:{
                                echo ' %';
                                break;
                              }
                              case 59:{
                                echo ' %';
                                break;
                              }
                              case 60:{
                                echo ' W/m²';
                                break;
                              }
                              case 61:{
                                echo ' W';
                                break;
                              }
                              case 62:{
                                echo ' W';
                                break;
                              }
                            }
                          ?>      
                          </h1></h4>
                    
                    <button type="submit" class="btn btn-primary" onclick= "location.href='index.php?value=-1&page=<?php echo $page; ?>'"><span class="glyphicon glyphicon-arrow-left"></span> Précédent</button>
                    <button type="submit" class="btn btn-success" onclick= "location.href='index.php?value=1&page=<?php echo $page; ?>'">Suivant <span class="glyphicon glyphicon-arrow-right"></span></button><br></br><br>

                    <h4>Modifier l'état des charges: <h1 class="text-center text-primary"> 
                    <?php 
                        if($chargesrows[0]['VALUE'] == 0) echo '<button type="submit" class="btn btn-danger" onclick= "location.href=\'updateCharge.php?chargeid=22&value=1\'"><span class="glyphicon glyphicon-cog"></span> C1 : Éteint</button> ';    
                        else echo '<button type="submit" class="btn btn-success" onclick= "location.href=\'updateCharge.php?chargeid=22&value=0\'"><span class="glyphicon glyphicon-cog"></span> C1 : Allumé</button> '; 

                        if($chargesrows[1]['VALUE'] == 0) echo '<button type="submit" class="btn btn-danger" onclick= "location.href=\'updateCharge.php?chargeid=23&value=1\'"><span class="glyphicon glyphicon-cog"></span> C2 : Éteint</button> ';    
                        else echo '<button type="submit" class="btn btn-success" onclick= "location.href=\'updateCharge.php?chargeid=23&value=0\'"><span class="glyphicon glyphicon-cog"></span> C2 : Allumé</button> ';
                        echo '<br>';

                        if($chargesrows[2]['VALUE'] == 0) echo '<button type="submit" class="btn btn-danger" onclick= "location.href=\'updateCharge.php?chargeid=24&value=1\'"><span class="glyphicon glyphicon-cog"></span> C3 : Éteint</button> ';    
                        else echo '<button type="submit" class="btn btn-success" onclick= "location.href=\'updateCharge.php?chargeid=24&value=0\'"><span class="glyphicon glyphicon-cog"></span> C3 : Allumé</button> ';

                        if($chargesrows[3]['VALUE'] == 0) echo '<button type="submit" class="btn btn-danger" onclick= "location.href=\'updateCharge.php?chargeid=25&value=1\'"><span class="glyphicon glyphicon-cog"></span> C4 : Éteint</button> ';    
                        else echo '<button type="submit" class="btn btn-success" onclick= "location.href=\'updateCharge.php?chargeid=25&value=0\'"><span class="glyphicon glyphicon-cog"></span> C4 : Allumé</button> ';
                    ?>
                    </div>
            </div>
            <div class="col-md-9">
                <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title"></h3>
                </div>
                <div class="box-body">
                  <div class="chart">
                    <canvas id="lineChart" style="height:250px"></canvas>
                  </div>
                </div>
            </div>
            </div>
        </div>
        <br>
      <footer class="footer text-center">
        © 2022 Data Logger by <a href="https://www.linkedin.com/in/yassine-bouelkheir/">BOUELKHEIR Yassine</a>
      </footer>
    </div>
    <?php include('data.php'); ?>
    <script>
      $(function () {
        var lineChartData = {
          labels  : [<?php echo $tjan; ?>, <?php echo $tfeb; ?>, <?php echo $tmar; ?>, <?php echo $tapr; ?>, <?php echo $tmay; ?>, <?php echo $tjun; ?>, <?php echo $tjul; ?>, <?php echo $taug; ?>, <?php echo $tsep; ?>, <?php echo $toct; ?>],
          datasets: [
            {
              label               : '<?php 
                            switch($page)
                            {
                              case 54:{
                                echo 'Courant DC';
                                break;
                              }
                              case 55:{
                                echo 'Courant AC';
                                break;
                              }
                              case 56:{
                                echo 'Tension DC';
                                break;
                              }
                              case 57:{
                                echo 'Température';
                                break;
                              }
                              case 58:{
                                echo 'Luminosité';
                                break;
                              }
                              case 59:{
                                echo 'Humidité';
                                break;
                              }
                              case 60:{
                                echo 'Irradiation';
                                break;
                              }
                              case 61:{
                                echo 'Puissance AC';
                                break;
                              }
                              case 62:{
                                echo 'Puissance DC';
                                break;
                              }
                            }
                          ?>',
              borderColor: '#0000FF',
              data                : [ "<?php echo $pjan; ?>",
                                      "<?php echo $pfeb; ?>",
                                      "<?php echo $pmar; ?>",
                                      "<?php echo $papr; ?>",
                                      "<?php echo $pmay; ?>",
                                      "<?php echo $pjun; ?>",
                                      "<?php echo $pjul; ?>",
                                      "<?php echo $paug; ?>",
                                      "<?php echo $psep; ?>",
                                      "<?php echo $poct; ?>"
                                    ]
            },
          ]
        }
     
        var lineChartCanvas          = $('#lineChart').get(0).getContext('2d')
        var lineChartOptions = {
          showScale               : true,
          parseTime               : false,
          scaleShowGridLines      : false,
          scaleGridLineColor      : 'rgba(255,255,255,.05)',
          scaleGridLineWidth      : 1,
          scaleShowHorizontalLines: true,
          scaleShowVerticalLines  : true,
          bezierCurve             : true,
          bezierCurveTension      : 0.3,
          pointDot                : false,
          pointDotRadius          : 4,
          pointDotStrokeWidth     : 1,
          pointHitDetectionRadius : 20,
          datasetStroke           : true,
          datasetStrokeWidth      : 2,
          datasetFill             : true,
          legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
          maintainAspectRatio     : true,
          responsive              : true
        }
        lineChartOptions.datasetFill = false
        var lineChart = new Chart(lineChartCanvas,{
            type: 'line',
            data: lineChartData,
            options: lineChartOptions
        })
      })

      function refresh() {
      $.ajax({
          url: './updateStaticValues.php',
          type: 'post',
          data: 'page=<?php echo ''.$page.''; ?>',
          dataType: "json",
          success: function (response) {
            document.getElementById('updateVal').innerHTML = response.value;
          }
       });
      }
      setInterval(function(){
          refresh() 
      }, 400);
    </script>
  </body>
</html>
